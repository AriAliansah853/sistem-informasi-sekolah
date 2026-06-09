<?php

namespace App\Http\Controllers;

use App\Models\TryOut;
use App\Models\HasilTryOut;
use App\Models\JawabanSiswa;
use App\Models\Guru;
use App\Services\JawabanService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HasilTryOutController extends Controller
{
    protected $jawabanService;

    public function __construct(JawabanService $jawabanService)
    {
        $this->jawabanService = $jawabanService;
        $this->middleware('auth');
    }

    /**
     * Display hasil try out untuk guru
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan');
        }

        $query = TryOut::where('guru_id', $guru->id);

        $tryOuts = $query->with('statistik')->latest()->get();

        return view('cbt.hasil.index', compact('tryOuts', 'guru'));
    }

    /**
     * Show hasil try out detail
     */
    public function show(TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return redirect()->back()->with('error', 'Anda tidak berhak mengakses halaman ini');
        }

        // Get ranking
        $ranking = $this->jawabanService->getRankingSiswa($tryOut->id);
        $statistik = $tryOut->statistik;

        return view('cbt.hasil.show', compact('tryOut', 'ranking', 'statistik', 'guru'));
    }

    /**
     * Show hasil siswa detail
     */
    public function showSiswa(HasilTryOut $hasilTryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($hasilTryOut->tryOut->guru_id !== $guru->id) {
            return redirect()->back()->with('error', 'Anda tidak berhak mengakses halaman ini');
        }

        $hasilTryOut = $hasilTryOut->load('siswa', 'tryOut', 'jawabanSiswas.bankSoal.pembahasan', 'jawabanSiswas.opsiSoal');

        return view('cbt.hasil.detail-siswa', compact('hasilTryOut', 'guru'));
    }

    /**
     * Export hasil to Excel
     */
    public function export(TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return redirect()->back()->with('error', 'Anda tidak berhak mengakses halaman ini');
        }

        $ranking = HasilTryOut::where('try_out_id', $tryOut->id)
            ->where('status', 'selesai')
            ->with('siswa')
            ->orderByDesc('skor_akhir')
            ->get();

        $fileName = 'hasil_' . Str::slug($tryOut->judul) . '_' . date('Y-m-d') . '.csv';
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName"
        );

        $columns = array('Rank', 'NIS', 'Nama Siswa', 'Skor', 'Nilai Huruf', 'Status', 'Jumlah Benar', 'Jumlah Salah', 'Durasi');

        $callback = function () use ($ranking, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($ranking as $index => $hasil) {
                fputcsv($file, array(
                    $index + 1,
                    $hasil->siswa->nis ?? '-',
                    $hasil->siswa->nama ?? '-',
                    number_format($hasil->skor_akhir, 2),
                    $hasil->nilai_huruf,
                    $hasil->status_kelulusan,
                    $hasil->jumlah_benar,
                    $hasil->jumlah_salah,
                    $this->formatSeconds($hasil->durasi_pengerjaan_detik)
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistik chart data
     */
    public function getStatistikChart(TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $hasil = HasilTryOut::where('try_out_id', $tryOut->id)
            ->where('status', 'selesai')
            ->get();

        // Distribusi nilai
        $ranges = [
            '0-20' => 0,
            '21-40' => 0,
            '41-60' => 0,
            '61-80' => 0,
            '81-100' => 0
        ];

        foreach ($hasil as $h) {
            if ($h->skor_akhir <= 20) $ranges['0-20']++;
            elseif ($h->skor_akhir <= 40) $ranges['21-40']++;
            elseif ($h->skor_akhir <= 60) $ranges['41-60']++;
            elseif ($h->skor_akhir <= 80) $ranges['61-80']++;
            else $ranges['81-100']++;
        }

        // Soal tersulit
        $soalTersulit = JawabanSiswa::where('try_out_id', $tryOut->id)
            ->where('is_correct', false)
            ->groupBy('bank_soal_id')
            ->selectRaw('bank_soal_id, COUNT(*) as total_salah')
            ->with('bankSoal')
            ->orderByDesc('total_salah')
            ->limit(5)
            ->get();

        return response()->json([
            'distribusi_nilai' => $ranges,
            'total_peserta' => $hasil->count(),
            'lulus' => $hasil->where('status_kelulusan', 'lulus')->count(),
            'belum_lulus' => $hasil->where('status_kelulusan', 'belum_lulus')->count(),
            'rata_rata' => round($hasil->avg('skor_akhir'), 2),
            'tertinggi' => round($hasil->max('skor_akhir'), 2),
            'terendah' => round($hasil->min('skor_akhir'), 2),
            'soal_tersulit' => $soalTersulit->map(fn($s) => [
                'judul' => $s->bankSoal->judul,
                'total_salah' => $s->total_salah
            ])
        ]);
    }

    /**
     * Format detik ke format jam:menit:detik
     */
    private function formatSeconds($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }
}
