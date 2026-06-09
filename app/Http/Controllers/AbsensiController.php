<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\OrangtuaNotification;
use App\Models\Siswa;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AbsensiController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $kelas = Kelas::where('guru_id', $guru->id)->get();

        return view('pages.guru.absensi.index', compact('guru', 'kelas'));
    }

    public function input($kelasId)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $kelas = Kelas::where('id', $kelasId)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        $tanggal = request()->input('tanggal', date('Y-m-d'));
        $siswas = Siswa::where('kelas_id', $kelas->id)->get();
        $absensis = Absensi::whereDate('tanggal', $tanggal)
            ->whereHas('siswa', function ($query) use ($kelas) {
                $query->where('kelas_id', $kelas->id);
            })
            ->get()
            ->keyBy('siswa_id');

        return view('pages.guru.absensi.input', compact('guru', 'kelas', 'siswas', 'tanggal', 'absensis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'required|exists:siswas,id',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        foreach ($request->siswa_id as $siswaId) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'status' => $request->status[$siswaId] ?? 'alpha',
                    'keterangan' => $request->keterangan[$siswaId] ?? null,
                ]
            );
        }

        $this->sendParentNotifications($request->tanggal, $request->siswa_id, $request->status, $request->keterangan ?? []);

        return redirect()->route('absensi.input', ['kelas' => $request->kelas_id, 'tanggal' => $request->tanggal])
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function laporan(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $kelas = Kelas::where('guru_id', $guru->id)->get();
        $kelasId = $request->input('kelas_id', optional($kelas->first())->id);
        $selectedKelas = Kelas::where('id', $kelasId)
            ->where('guru_id', $guru->id)
            ->first();

        $absensi = collect();
        $totals = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];
        $periodLabel = $this->getPeriodLabel($request);

        if ($selectedKelas) {
            $absensi = $this->getAbsensiQuery($selectedKelas, $request)->get();
            $counts = $absensi->countBy('status')->only(['hadir', 'izin', 'sakit', 'alpha'])->toArray();
            $totals = array_merge($totals, $counts);
        }

        return view('pages.guru.absensi.laporan', compact('guru', 'kelas', 'selectedKelas', 'absensi', 'totals', 'periodLabel'));
    }

    public function exportExcel(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $selectedKelas = Kelas::where('id', $request->input('kelas_id'))
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        $absensi = $this->getAbsensiQuery($selectedKelas, $request)->get();
        $periodLabel = $this->getPeriodLabel($request);
        $filename = 'laporan-absensi-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', strtolower($selectedKelas->nama_kelas)) . '-' . str_replace(' ', '_', strtolower($periodLabel)) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($absensi) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama Siswa', 'NIS', 'Status', 'Keterangan']);

            foreach ($absensi as $index => $data) {
                fputcsv($file, [
                    $index + 1,
                    $data->siswa->nama,
                    $data->siswa->nis,
                    ucfirst($data->status),
                    $data->keterangan,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $selectedKelas = Kelas::where('id', $request->input('kelas_id'))
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        $absensi = $this->getAbsensiQuery($selectedKelas, $request)->get();
        $periodLabel = $this->getPeriodLabel($request);
        $html = view('pages.guru.absensi.pdf', compact('selectedKelas', 'absensi', 'periodLabel'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'laporan-absensi-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', strtolower($selectedKelas->nama_kelas)) . '-' . str_replace(' ', '_', strtolower($periodLabel)) . '.pdf';

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    protected function getAbsensiQuery(Kelas $kelas, Request $request)
    {
        $query = Absensi::with('siswa')
            ->whereHas('siswa', function ($query) use ($kelas) {
                $query->where('kelas_id', $kelas->id);
            });

        if ($request->filled('bulan')) {
            $query->whereYear('tanggal', $request->input('tahun', date('Y')))
                ->whereMonth('tanggal', $request->input('bulan'));
        } elseif ($request->filled('semester')) {
            $year = $request->input('tahun', date('Y'));
            if ($request->input('semester') == 1) {
                $query->whereBetween('tanggal', ["{$year}-01-01", "{$year}-06-30"]);
            } else {
                $query->whereBetween('tanggal', ["{$year}-07-01", "{$year}-12-31"]);
            }
        } else {
            $query->whereDate('tanggal', $request->input('tanggal', date('Y-m-d')));
        }

        return $query;
    }

    protected function getPeriodLabel(Request $request)
    {
        if ($request->filled('bulan')) {
            return Carbon::createFromDate($request->input('tahun', date('Y')), $request->input('bulan'), 1)->translatedFormat('F Y');
        }

        if ($request->filled('semester')) {
            return 'Semester ' . $request->input('semester') . ' ' . $request->input('tahun', date('Y'));
        }

        return Carbon::parse($request->input('tanggal', date('Y-m-d')))->translatedFormat('d F Y');
    }

    protected function sendParentNotifications(string $tanggal, array $siswaIds, array $statuses, array $keterangan)
    {
        foreach ($siswaIds as $siswaId) {
            $status = $statuses[$siswaId] ?? 'alpha';
            if ($status === 'hadir') {
                continue;
            }

            $siswa = Siswa::with('orangtua')->find($siswaId);
            if (! $siswa) {
                continue;
            }

            $message = "Absensi siswa {$siswa->nama} tanggal {$tanggal} tercatat sebagai {$status}.";
            if (! empty($keterangan[$siswaId])) {
                $message .= ' Keterangan: ' . $keterangan[$siswaId];
            }

            foreach ($siswa->orangtua as $orangtua) {
                OrangtuaNotification::create([
                    'orangtua_id' => $orangtua->id,
                    'siswa_id' => $siswa->id,
                    'title' => "Notifikasi Absensi: {$siswa->nama}",
                    'message' => $message,
                ]);
            }
        }
    }
}
