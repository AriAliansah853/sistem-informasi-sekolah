<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\Mapel;
use App\Models\Guru;
use App\Services\BankSoalService;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    protected $soalService;

    public function __construct(BankSoalService $soalService)
    {
        $this->soalService = $soalService;
        $this->middleware('auth');
        $this->middleware('checkRole:guru');
    }

    /**
     * Display list of soal
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            return redirect()->route('guru.dashboard')->with('error', 'Data guru tidak ditemukan');
        }

        $mapels = Mapel::all();
        $query = BankSoal::where('guru_id', $guru->id);

        if ($request->mapel_id) {
            $query->where('mapel_id', $request->mapel_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $soals = $query->with('mapel', 'opsiSoal')
            ->latest()
            ->paginate(15);

        return view('cbt.soal.index', compact('soals', 'mapels', 'guru'));
    }

    /**
     * Show form create soal
     */
    public function create()
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();
        $mapels = Mapel::all();

        return view('cbt.soal.create', compact('guru', 'mapels'));
    }

    /**
     * Store soal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:pilihan_ganda,essay,benar_salah',
            'tingkat_kesulitan' => 'required|integer|min:1|max:5',
            'bobot' => 'required|integer|min:1',
            'pembahasan' => 'nullable|string',
            'kata_kunci' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'opsi_soal.*.kode' => 'required_if:tipe,pilihan_ganda,benar_salah|string',
            'opsi_soal.*.teks' => 'required_if:tipe,pilihan_ganda,benar_salah|string',
            'opsi_soal.*.is_correct' => 'boolean'
        ]);

        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        try {
            $validated['guru_id'] = $guru->id;
            $soal = $this->soalService->createSoal($validated);

            return redirect()->route('bank-soal.show', $soal->id)
                ->with('success', 'Soal berhasil dibuat');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show soal detail
     */
    public function show(BankSoal $bankSoal)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($bankSoal->guru_id !== $guru->id) {
            return redirect()->route('bank-soal.index')
                ->with('error', 'Anda tidak berhak mengakses soal ini');
        }

        $bankSoal = $this->soalService->getSoal($bankSoal);

        return view('cbt.soal.show', compact('bankSoal'));
    }

    /**
     * Show form edit
     */
    public function edit(BankSoal $bankSoal)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($bankSoal->guru_id !== $guru->id) {
            return redirect()->route('bank-soal.index')
                ->with('error', 'Anda tidak berhak mengakses soal ini');
        }

        $mapels = Mapel::all();
        $bankSoal = $this->soalService->getSoal($bankSoal);

        return view('cbt.soal.edit', compact('bankSoal', 'mapels', 'guru'));
    }

    /**
     * Update soal
     */
    public function update(Request $request, BankSoal $bankSoal)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($bankSoal->guru_id !== $guru->id) {
            return redirect()->route('bank-soal.index')
                ->with('error', 'Anda tidak berhak mengakses soal ini');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tingkat_kesulitan' => 'required|integer|min:1|max:5',
            'bobot' => 'required|integer|min:1',
            'pembahasan' => 'nullable|string',
            'kata_kunci' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'opsi_soal.*.kode' => 'required_if:tipe,pilihan_ganda,benar_salah|string',
            'opsi_soal.*.teks' => 'required_if:tipe,pilihan_ganda,benar_salah|string',
            'opsi_soal.*.is_correct' => 'boolean'
        ]);

        try {
            $bankSoal = $this->soalService->updateSoal($bankSoal, $validated);

            return redirect()->route('bank-soal.show', $bankSoal->id)
                ->with('success', 'Soal berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete soal
     */
    public function destroy(BankSoal $bankSoal)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($bankSoal->guru_id !== $guru->id) {
            return redirect()->route('bank-soal.index')
                ->with('error', 'Anda tidak berhak mengakses soal ini');
        }

        try {
            $this->soalService->deleteSoal($bankSoal);

            return redirect()->route('bank-soal.index')
                ->with('success', 'Soal berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Publish soal
     */
    public function publish(BankSoal $bankSoal)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        if ($bankSoal->guru_id !== $guru->id) {
            return redirect()->route('bank-soal.index')
                ->with('error', 'Anda tidak berhak mengakses soal ini');
        }

        $this->soalService->publishSoal($bankSoal);

        return redirect()->route('bank-soal.show', $bankSoal->id)
            ->with('success', 'Soal berhasil dipublikasikan');
    }
}
