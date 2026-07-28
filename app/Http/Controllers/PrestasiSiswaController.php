<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\PrestasiSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestasiSiswaController extends Controller
{
    public function index()
    {
        $prestasis = PrestasiSiswa::orderBy('tahun', 'desc')->orderBy('id', 'desc')->get();

        return view('pages.admin.prestasi-siswa.index', compact('prestasis'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'jenis_prestasi' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_siswa.required' => 'Nama siswa harus diisi.',
            'kelas.required' => 'Kelas siswa harus diisi.',
            'jenis_prestasi.required' => 'Jenis prestasi harus diisi.',
            'tahun.required' => 'Tahun prestasi harus diisi.',
            'tahun.integer' => 'Tahun prestasi harus berupa angka.',
        ]);

        try {
            DB::beginTransaction();

            PrestasiSiswa::create($validated);

            DB::commit();

            return redirect()->route('prestasi-siswa.index')->with('success', 'Prestasi siswa berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan prestasi siswa: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        $prestasi = PrestasiSiswa::findOrFail(BaseModel::decodeRouteKey($id));

        return view('pages.admin.prestasi-siswa.edit', compact('prestasi'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'jenis_prestasi' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_siswa.required' => 'Nama siswa harus diisi.',
            'kelas.required' => 'Kelas siswa harus diisi.',
            'jenis_prestasi.required' => 'Jenis prestasi harus diisi.',
            'tahun.required' => 'Tahun prestasi harus diisi.',
            'tahun.integer' => 'Tahun prestasi harus berupa angka.',
        ]);

        try {
            DB::beginTransaction();

            $prestasi = PrestasiSiswa::findOrFail(BaseModel::decodeRouteKey($id));
            $prestasi->update($validated);

            DB::commit();

            return redirect()->route('prestasi-siswa.index')->with('success', 'Prestasi siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui prestasi siswa: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $prestasi = PrestasiSiswa::findOrFail(BaseModel::decodeRouteKey($id));
            $prestasi->delete();

            DB::commit();

            return redirect()->route('prestasi-siswa.index')->with('success', 'Prestasi siswa berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus prestasi siswa: ' . $e->getMessage());
        }
    }
}
