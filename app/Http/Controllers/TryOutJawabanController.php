<?php

namespace App\Http\Controllers;

use App\Models\TryOut;
use App\Models\HasilTryOut;
use App\Models\JawabanSiswa;
use App\Models\Siswa;
use App\Services\JawabanService;
use Illuminate\Http\Request;

class TryOutJawabanController extends Controller
{
    protected $jawabanService;

    public function __construct(JawabanService $jawabanService)
    {
        $this->jawabanService = $jawabanService;
        $this->middleware('auth');
        $this->middleware('checkRole:siswa');
    }

    /**
     * Display daftar try out untuk siswa
     */
    public function indexForSiswa(Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return redirect()->route('home')->with('error', 'Data siswa tidak ditemukan');
        }

        // Get try out yang bisa diakses (sesuai kelas atau public)
        $query = TryOut::where('status', 'active')
            ->where('waktu_mulai', '<=', now())
            ->where('waktu_selesai', '>=', now());

        if ($siswa->kelas_id) {
            $query->where(function ($q) use ($siswa) {
                $q->where('kelas_id', $siswa->kelas_id)
                  ->orWhereNull('kelas_id');
            });
        }

        $tryOuts = $query->with('mapel', 'guru')
            ->latest()
            ->paginate(15);

        // Get hasil try out siswa ini
        $hasilSiswa = HasilTryOut::where('siswa_id', $siswa->id)
            ->pluck('try_out_id')
            ->toArray();

        return view('cbt.jawaban.index-siswa', compact('tryOuts', 'siswa', 'hasilSiswa'));
    }

    /**
     * Show form try out untuk siswa
     */
    public function showForSiswa(TryOut $tryOut)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return redirect()->route('home')->with('error', 'Data siswa tidak ditemukan');
        }

        // Check if siswa sudah pernah mengerjakan
        $hasilSebelumnya = HasilTryOut::where('siswa_id', $siswa->id)
            ->where('try_out_id', $tryOut->id)
            ->first();

        if ($hasilSebelumnya && $hasilSebelumnya->status === 'selesai') {
            return redirect()->route('try-out-jawaban.hasil', $hasilSebelumnya->id)
                ->with('info', 'Anda sudah menyelesaikan try out ini');
        }

        // Check if current time is in range
        if (now() < $tryOut->waktu_mulai || now() > $tryOut->waktu_selesai) {
            return redirect()->route('try-out-jawaban.index-siswa')
                ->with('error', 'Try out tidak tersedia pada waktu ini');
        }

        // Get soal dengan acak
        $soals = $tryOut->tryOutSoals()
            ->with('bankSoal.opsiSoal')
            ->orderBy('urutan')
            ->get();

        // Acak soal jika diperlukan
        if ($tryOut->acak_soal) {
            $soals = $soals->shuffle();
        }

        // Transform untuk ditampilkan
        $soalFormatted = $soals->map(function ($tryOutSoal, $index) use ($tryOut) {
            $soal = $tryOutSoal->bankSoal;
            $opsiSoal = collect();

            if ($soal->tipe === 'pilihan_ganda' || $soal->tipe === 'benar_salah') {
                $opsiSoal = $soal->opsiSoal;
                if ($tryOut->acak_opsi) {
                    $opsiSoal = $opsiSoal->shuffle();
                }
            }

            return [
                'try_out_soal_id' => $tryOutSoal->id,
                'urutan' => $index + 1,
                'bank_soal_id' => $soal->id,
                'judul' => $soal->judul,
                'isi' => $soal->isi,
                'tipe' => $soal->tipe,
                'opsi_soal' => $opsiSoal->map(fn($o) => [
                    'id' => $o->id,
                    'kode' => $o->kode,
                    'teks' => $o->teks
                ])
            ];
        })->values();

        $savedAnswers = JawabanSiswa::where('siswa_id', $siswa->id)
            ->where('try_out_id', $tryOut->id)
            ->get()
            ->mapWithKeys(function ($jawaban) use ($soalFormatted) {
                $index = $soalFormatted->search(fn($item) => $item['try_out_soal_id'] === $jawaban->try_out_soal_id);
                return $index !== false ? [
                    $index => [
                        'jawaban' => $jawaban->jawaban,
                        'opsi_soal_id' => $jawaban->opsi_soal_id,
                        'status' => $jawaban->status
                    ]
                ] : [];
            });

        return view('cbt.jawaban.kerjakan-tryout', compact('tryOut', 'siswa', 'soalFormatted', 'hasilSebelumnya', 'savedAnswers'));
    }

    /**
     * Save jawaban siswa via AJAX
     */
    public function saveJawaban(Request $request)
    {
        $validated = $request->validate([
            'try_out_id' => 'required|exists:try_outs,id',
            'try_out_soal_id' => 'required|exists:try_out_soals,id',
            'bank_soal_id' => 'required|exists:bank_soals,id',
            'jawaban' => 'nullable|string',
            'opsi_soal_id' => 'nullable|exists:opsi_soals,id',
            'waktu_dikerjakan_detik' => 'nullable|integer'
        ]);

        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        try {
            $validated['siswa_id'] = $siswa->id;
            $jawaban = $this->jawabanService->saveJawaban($validated);

            return response()->json([
                'message' => 'Jawaban berhasil disimpan',
                'jawaban' => $jawaban
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Selesaikan try out
     */
    public function finalize(Request $request)
    {
        $validated = $request->validate([
            'try_out_id' => 'required|exists:try_outs,id'
        ]);

        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        try {
            $hasil = $this->jawabanService->finalizeTryOut($siswa->id, $validated['try_out_id']);

            return response()->json([
                'message' => 'Try out berhasil diselesaikan',
                'hasil_id' => $hasil->id,
                'skor_akhir' => $hasil->skor_akhir
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Show hasil try out
     */
    public function showHasil(HasilTryOut $hasilTryOut)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa || $hasilTryOut->siswa_id !== $siswa->id) {
            return redirect()->route('home')
                ->with('error', 'Anda tidak berhak mengakses halaman ini');
        }

        $hasilTryOut = $hasilTryOut->load('siswa', 'tryOut', 'jawabanSiswas.bankSoal.pembahasan');
        $tryOut = $hasilTryOut->tryOut;

        // Only show hasil if try out sudah selesai atau show_hasil_langsung true
        if (now() < $tryOut->waktu_selesai && !$tryOut->show_hasil_langsung) {
            return redirect()->route('try-out-jawaban.index-siswa')
                ->with('info', 'Hasil belum bisa dilihat sampai ujian selesai');
        }

        return view('cbt.jawaban.hasil-tryout', compact('hasilTryOut'));
    }
}
