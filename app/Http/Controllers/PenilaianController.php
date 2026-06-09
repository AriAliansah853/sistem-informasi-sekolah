<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pengaturan;
use App\Models\PenilaianSiswa;
use App\Models\Siswa;
use App\Services\PenilaianService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PenilaianController extends Controller
{
    protected $penilaianService;

    public function __construct(PenilaianService $penilaianService)
    {
        $this->penilaianService = $penilaianService;
    }

    public function index(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->with('mapel')->firstOrFail();
        $kelas = Kelas::where('guru_id', $guru->id)->get();

        $query = PenilaianSiswa::with(['siswa', 'kelas', 'mapel'])
            ->where('mapel_id', $guru->mapel_id);

        $query = $this->applyFilter($query, $request);

        $penilaians = $query->orderByDesc('tahun')
            ->orderByDesc('semester')
            ->orderByDesc('nilai_akhir')
            ->paginate(20);

        return view('pages.guru.penilaian.index', compact('guru', 'kelas', 'penilaians', 'request'));
    }

    public function create(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->with('mapel')->firstOrFail();
        $kelas = Kelas::where('guru_id', $guru->id)->get();
        $selectedKelas = null;
        $siswas = collect();
        $existing = collect();
        $semester = $request->input('semester', 1);
        $tahun = $request->input('tahun', date('Y'));

        if ($request->filled('kelas_id')) {
            $selectedKelas = $kelas->firstWhere('id', $request->kelas_id);
        }

        if ($selectedKelas) {
            $siswas = Siswa::where('kelas_id', $selectedKelas->id)->get();
            $existing = PenilaianSiswa::where('kelas_id', $selectedKelas->id)
                ->where('mapel_id', $guru->mapel_id)
                ->where('semester', $semester)
                ->where('tahun', $tahun)
                ->get()
                ->keyBy('siswa_id');
        }

        return view('pages.guru.penilaian.create', compact('guru', 'kelas', 'selectedKelas', 'siswas', 'existing', 'semester', 'tahun'));
    }

    public function store(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'semester' => 'required|in:1,2',
            'tahun' => 'required|digits:4',
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'required|exists:siswas,id',
            'nilai_harian.*' => 'nullable|numeric|min:0|max:100',
            'nilai_tugas.*' => 'nullable|numeric|min:0|max:100',
            'nilai_uts.*' => 'nullable|numeric|min:0|max:100',
            'nilai_uas.*' => 'nullable|numeric|min:0|max:100',
            'nilai_sikap.*' => 'nullable|numeric|min:0|max:100',
            'nilai_kehadiran.*' => 'nullable|numeric|min:0|max:100',
            'bobot_harian' => 'nullable|numeric|min:0|max:100',
            'bobot_tugas' => 'nullable|numeric|min:0|max:100',
            'bobot_uts' => 'nullable|numeric|min:0|max:100',
            'bobot_uas' => 'nullable|numeric|min:0|max:100',
            'bobot_sikap' => 'nullable|numeric|min:0|max:100',
            'bobot_kehadiran' => 'nullable|numeric|min:0|max:100',
        ]);

        $kelas = Kelas::where('id', $request->kelas_id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        $weights = [
            'nilai_harian' => $request->input('bobot_harian', 20),
            'nilai_tugas' => $request->input('bobot_tugas', 20),
            'nilai_uts' => $request->input('bobot_uts', 25),
            'nilai_uas' => $request->input('bobot_uas', 25),
            'nilai_sikap' => $request->input('bobot_sikap', 5),
            'nilai_kehadiran' => $request->input('bobot_kehadiran', 5),
        ];

        foreach ($request->siswa_id as $siswaId) {
            $scores = [
                'nilai_harian' => $request->input('nilai_harian.' . $siswaId, 0),
                'nilai_tugas' => $request->input('nilai_tugas.' . $siswaId, 0),
                'nilai_uts' => $request->input('nilai_uts.' . $siswaId, 0),
                'nilai_uas' => $request->input('nilai_uas.' . $siswaId, 0),
                'nilai_sikap' => $request->input('nilai_sikap.' . $siswaId, 0),
                'nilai_kehadiran' => $request->input('nilai_kehadiran.' . $siswaId, 0),
            ];

            $nilaiAkhir = $this->penilaianService->calculateFinalScore($scores, $weights);
            $nilaiRataRata = $this->penilaianService->calculateAverage($scores);

            PenilaianSiswa::updateOrCreate([
                'siswa_id' => $siswaId,
                'kelas_id' => $kelas->id,
                'mapel_id' => $guru->mapel_id,
                'semester' => $request->semester,
                'tahun' => $request->tahun,
            ], array_merge($scores, [
                'guru_id' => $guru->id,
                'bobot_harian' => $weights['nilai_harian'],
                'bobot_tugas' => $weights['nilai_tugas'],
                'bobot_uts' => $weights['nilai_uts'],
                'bobot_uas' => $weights['nilai_uas'],
                'bobot_sikap' => $weights['nilai_sikap'],
                'bobot_kehadiran' => $weights['nilai_kehadiran'],
                'nilai_akhir' => $nilaiAkhir,
                'nilai_rata_rata' => $nilaiRataRata,
            ]));
        }

        return redirect()->route('penilaian.index', [
            'kelas_id' => $kelas->id,
            'semester' => $request->semester,
            'tahun' => $request->tahun,
        ])->with('success', 'Data penilaian siswa berhasil disimpan.');
    }

    public function edit(PenilaianSiswa $penilaian)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        abort_unless($penilaian->guru_id === $guru->id, 403);

        return view('pages.guru.penilaian.edit', compact('penilaian'));
    }

    public function update(Request $request, PenilaianSiswa $penilaian)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        abort_unless($penilaian->guru_id === $guru->id, 403);

        $request->validate([
            'nilai_harian' => 'nullable|numeric|min:0|max:100',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
            'nilai_sikap' => 'nullable|numeric|min:0|max:100',
            'nilai_kehadiran' => 'nullable|numeric|min:0|max:100',
            'bobot_harian' => 'nullable|numeric|min:0|max:100',
            'bobot_tugas' => 'nullable|numeric|min:0|max:100',
            'bobot_uts' => 'nullable|numeric|min:0|max:100',
            'bobot_uas' => 'nullable|numeric|min:0|max:100',
            'bobot_sikap' => 'nullable|numeric|min:0|max:100',
            'bobot_kehadiran' => 'nullable|numeric|min:0|max:100',
        ]);

        $scores = [
            'nilai_harian' => $request->input('nilai_harian', 0),
            'nilai_tugas' => $request->input('nilai_tugas', 0),
            'nilai_uts' => $request->input('nilai_uts', 0),
            'nilai_uas' => $request->input('nilai_uas', 0),
            'nilai_sikap' => $request->input('nilai_sikap', 0),
            'nilai_kehadiran' => $request->input('nilai_kehadiran', 0),
        ];

        $weights = [
            'nilai_harian' => $request->input('bobot_harian', $penilaian->bobot_harian),
            'nilai_tugas' => $request->input('bobot_tugas', $penilaian->bobot_tugas),
            'nilai_uts' => $request->input('bobot_uts', $penilaian->bobot_uts),
            'nilai_uas' => $request->input('bobot_uas', $penilaian->bobot_uas),
            'nilai_sikap' => $request->input('bobot_sikap', $penilaian->bobot_sikap),
            'nilai_kehadiran' => $request->input('bobot_kehadiran', $penilaian->bobot_kehadiran),
        ];

        $nilaiAkhir = $this->penilaianService->calculateFinalScore($scores, $weights);
        $nilaiRataRata = $this->penilaianService->calculateAverage($scores);

        $penilaian->update(array_merge($scores, [
            'bobot_harian' => $weights['nilai_harian'],
            'bobot_tugas' => $weights['nilai_tugas'],
            'bobot_uts' => $weights['nilai_uts'],
            'bobot_uas' => $weights['nilai_uas'],
            'bobot_sikap' => $weights['nilai_sikap'],
            'bobot_kehadiran' => $weights['nilai_kehadiran'],
            'nilai_akhir' => $nilaiAkhir,
            'nilai_rata_rata' => $nilaiRataRata,
        ]));

        return redirect()->route('penilaian.index', [
            'kelas_id' => $penilaian->kelas_id,
            'semester' => $penilaian->semester,
            'tahun' => $penilaian->tahun,
        ])->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function rekap(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->with('mapel')->firstOrFail();
        $kelas = Kelas::where('guru_id', $guru->id)->get();

        $query = PenilaianSiswa::with(['siswa', 'kelas', 'mapel'])
            ->where('mapel_id', $guru->mapel_id);

        $query = $this->applyFilter($query, $request);

        $penilaians = $query->orderByDesc('nilai_akhir')->get();

        $ranks = $penilaians->groupBy('kelas_id')->map(function ($group) {
            return $group->sortByDesc('nilai_akhir')->values()->mapWithKeys(function ($penilaian, $index) {
                return [$penilaian->id => $index + 1];
            });
        });

        return view('pages.guru.penilaian.rekap', compact('guru', 'kelas', 'penilaians', 'request', 'ranks'));
    }

    public function exportStudentPdf(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kelas_id' => 'required|exists:kelas,id',
            'semester' => 'required|in:1,2',
            'tahun' => 'required|digits:4',
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);
        $penilaians = PenilaianSiswa::with(['kelas', 'mapel', 'guru'])
            ->where('siswa_id', $siswa->id)
            ->where('kelas_id', $request->kelas_id)
            ->where('semester', $request->semester)
            ->where('tahun', $request->tahun)
            ->get();

        abort_if($penilaians->isEmpty(), 404, 'Data penilaian tidak ditemukan.');

        if (! in_array(Auth::user()->roles, ['guru', 'siswa'])) {
            abort(403);
        }

        if (Auth::user()->roles === 'siswa') {
            $siswaUser = Siswa::where('user_id', Auth::id())->firstOrFail();
            abort_unless($siswaUser->id === $siswa->id, 403);
        }

        if (Auth::user()->roles === 'guru') {
            $guru = Guru::where('user_id', Auth::id())->firstOrFail();
            abort_unless($penilaians->first()->guru_id === $guru->id, 403);
        }

        $pengaturan = Pengaturan::first();
        $averageScore = round($penilaians->avg('nilai_akhir'), 2);
        $rank = $this->penilaianService->getStudentSemesterRank($request->kelas_id, $request->semester, $request->tahun, $siswa->id);

        $html = view('pages.guru.penilaian.pdf', compact('pengaturan', 'siswa', 'penilaians', 'averageScore', 'rank', 'request'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = sprintf('rapor-%s-%s-semester-%s.pdf', strtolower(str_replace(' ', '-', $siswa->nama)), $request->tahun, $request->semester);

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function studentIndex(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $penilaians = PenilaianSiswa::with(['kelas', 'mapel', 'guru'])
            ->where('siswa_id', $siswa->id)
            ->orderByDesc('tahun')
            ->orderByDesc('semester')
            ->orderByDesc('nilai_akhir')
            ->get();

        return view('pages.siswa.penilaian.index', compact('penilaians'));
    }

    public function studentShow(PenilaianSiswa $penilaian)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        abort_unless($penilaian->siswa_id === $siswa->id, 403);

        return view('pages.siswa.penilaian.show', compact('penilaian'));
    }

    protected function applyFilter($query, Request $request)
    {
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        return $query;
    }
}
