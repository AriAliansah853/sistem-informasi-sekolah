<?php

namespace App\Http\Controllers;

use App\Models\TryOut;
use App\Models\BankSoal;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Services\TryOutService;
use Illuminate\Http\Request;

class TryOutController extends Controller
{
    protected $tryOutService;

    public function __construct(TryOutService $tryOutService)
    {
        $this->tryOutService = $tryOutService;
        $this->middleware('auth');
        $this->middleware('checkRole:guru')->except('indexForSiswa', 'showForSiswa');
        $this->middleware('checkRole:siswa', ['only' => ['indexForSiswa', 'showForSiswa']]);
    }

    /**
     * Display try out list untuk guru
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            return redirect()->route('guru.dashboard')->with('error', 'Data guru tidak ditemukan');
        }

        $query = TryOut::where('guru_id', $guru->id);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tryOuts = $query->with('mapel', 'kelas', 'statistik')
            ->latest()
            ->paginate(15);

        return view('cbt.tryout.index', compact('tryOuts', 'guru'));
    }

    /**
     * Show form create try out
     */
    public function create()
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();
        $mapels = Mapel::all();
        $kelas = Kelas::all();

        return view('cbt.tryout.create', compact('guru', 'mapels', 'kelas'));
    }

    /**
     * Store try out
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'waktu_mulai' => 'required|date_format:Y-m-d H:i',
            'waktu_selesai' => 'required|date_format:Y-m-d H:i|after:waktu_mulai',
            'durasi_menit' => 'required|integer|min:5|max:480',
            'jumlah_soal' => 'required|integer|min:1',
            'acak_soal' => 'boolean',
            'acak_opsi' => 'boolean',
            'show_hasil_langsung' => 'boolean',
            'show_pembahasan_langsung' => 'boolean',
            'passing_grade' => 'required|integer|min:0|max:100'
        ]);

        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        try {
            $validated['guru_id'] = $guru->id;
            $validated['status'] = 'draft';
            $validated['waktu_mulai'] = date('Y-m-d H:i:s', strtotime($validated['waktu_mulai']));
            $validated['waktu_selesai'] = date('Y-m-d H:i:s', strtotime($validated['waktu_selesai']));

            $tryOut = $this->tryOutService->createTryOut($validated);

            return redirect()->route('try-out.edit-soal', $tryOut->id)
                ->with('success', 'Try out berhasil dibuat. Sekarang pilih soal-soal');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show try out detail
     */
    public function show(TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return redirect()->route('try-out.index')
                ->with('error', 'Anda tidak berhak mengakses try out ini');
        }

        $tryOut = $this->tryOutService->getTryOut($tryOut);

        return view('cbt.tryout.show', compact('tryOut'));
    }

    /**
     * Show form edit try out
     */
    public function edit(TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return redirect()->route('try-out.index')
                ->with('error', 'Anda tidak berhak mengakses try out ini');
        }

        $mapels = Mapel::all();
        $kelas = Kelas::all();

        return view('cbt.tryout.edit', compact('tryOut', 'mapels', 'kelas'));
    }

    /**
     * Update try out
     */
    public function update(Request $request, TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return redirect()->route('try-out.index')
                ->with('error', 'Anda tidak berhak mengakses try out ini');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'waktu_mulai' => 'required|date_format:Y-m-d H:i',
            'waktu_selesai' => 'required|date_format:Y-m-d H:i|after:waktu_mulai',
            'durasi_menit' => 'required|integer|min:5|max:480',
            'acak_soal' => 'boolean',
            'acak_opsi' => 'boolean',
            'show_hasil_langsung' => 'boolean',
            'show_pembahasan_langsung' => 'boolean',
            'passing_grade' => 'required|integer|min:0|max:100'
        ]);

        try {
            $validated['waktu_mulai'] = date('Y-m-d H:i:s', strtotime($validated['waktu_mulai']));
            $validated['waktu_selesai'] = date('Y-m-d H:i:s', strtotime($validated['waktu_selesai']));

            $tryOut = $this->tryOutService->updateTryOut($tryOut, $validated);

            return redirect()->route('try-out.show', $tryOut->id)
                ->with('success', 'Try out berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show form edit soal
     */
    public function editSoal(TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return redirect()->route('try-out.index')
                ->with('error', 'Anda tidak berhak mengakses try out ini');
        }

        $soalsTerpilih = $tryOut->tryOutSoals()->pluck('bank_soal_id')->toArray();
        $soalsAvailable = BankSoal::where('mapel_id', $tryOut->mapel_id)
            ->where('status', 'published')
            ->whereNotIn('id', $soalsTerpilih)
            ->get();

        $soalsTryOut = $tryOut->tryOutSoals()->with('bankSoal')->get();

        return view('cbt.tryout.edit-soal', compact('tryOut', 'soalsAvailable', 'soalsTryOut'));
    }

    /**
     * Add soal to try out
     */
    public function addSoal(Request $request, TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'soal_ids' => 'required|array',
            'soal_ids.*' => 'exists:bank_soals,id'
        ]);

        try {
            $this->tryOutService->addSoalsToTryOut($tryOut, $validated['soal_ids']);

            return response()->json(['message' => 'Soal berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Delete try out
     */
    public function destroy(TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return redirect()->route('try-out.index')
                ->with('error', 'Anda tidak berhak mengakses try out ini');
        }

        try {
            $this->tryOutService->deleteTryOut($tryOut);

            return redirect()->route('try-out.index')
                ->with('success', 'Try out berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Publish try out
     */
    public function publish(TryOut $tryOut)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($tryOut->guru_id !== $guru->id) {
            return redirect()->route('try-out.index')
                ->with('error', 'Anda tidak berhak mengakses try out ini');
        }

        try {
            $this->tryOutService->publishTryOut($tryOut);

            return redirect()->route('try-out.show', $tryOut->id)
                ->with('success', 'Try out berhasil dipublikasikan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get available soal via AJAX
     */
    public function getAvailableSoals(Request $request)
    {
        $mapelId = $request->query('mapel_id');
        $excludeIds = $request->query('exclude_ids', []);

        $soals = BankSoal::where('mapel_id', $mapelId)
            ->where('status', 'published')
            ->whereNotIn('id', $excludeIds)
            ->get(['id', 'judul', 'tipe', 'tingkat_kesulitan']);

        return response()->json($soals);
    }
}
