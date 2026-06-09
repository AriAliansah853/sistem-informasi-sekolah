<?php

namespace App\Http\Controllers;

use App\Models\PpdbApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PpdbApplicantController extends Controller
{
    public function create()
    {
        return view('ppdb.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'alamat' => 'required|string|max:1000',
            'no_telp' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'sekolah_asal' => 'nullable|string|max:255',
            'jurusan_pilihan' => 'nullable|string|max:255',
            'nama_ortu' => 'nullable|string|max:255',
            'telp_ortu' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            PpdbApplicant::create($validated);

            DB::commit();

            return redirect()->route('ppdb.thanks')->with('success', 'Pendaftaran PPDB berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengirim pendaftaran: ' . $e->getMessage());
        }
    }

    public function thanks()
    {
        return view('ppdb.thanks');
    }

    public function index()
    {
        $applicants = PpdbApplicant::orderBy('created_at', 'desc')->get();

        return view('pages.admin.ppdb-applicant.index', compact('applicants'));
    }

    public function show($id)
    {
        $applicant = PpdbApplicant::findOrFail($id);

        return view('pages.admin.ppdb-applicant.show', compact('applicant'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $applicant = PpdbApplicant::findOrFail($id);
            $applicant->update($validated);

            DB::commit();

            return redirect()->route('ppdb-applicant.show', $applicant->id)->with('success', 'Status pendaftaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $applicant = PpdbApplicant::findOrFail($id);
            $applicant->delete();

            DB::commit();

            return redirect()->route('ppdb-applicant.index')->with('success', 'Pendaftaran PPDB berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pendaftaran: ' . $e->getMessage());
        }
    }
}
