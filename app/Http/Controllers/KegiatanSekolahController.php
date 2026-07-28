<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\KegiatanSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KegiatanSekolahController extends Controller
{
    public function index()
    {
        $kegiatans = KegiatanSekolah::orderBy('tanggal', 'desc')->get();

        return view('pages.admin.kegiatan.index', compact('kegiatans'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], [
            'title.required' => 'Judul kegiatan harus diisi.',
            'description.required' => 'Deskripsi kegiatan harus diisi.',
            'tanggal.required' => 'Tanggal kegiatan harus diisi.',
            'tanggal.date' => 'Tanggal kegiatan harus berupa tanggal valid.',
            'lokasi.required' => 'Lokasi kegiatan harus diisi.',
            'photo.image' => 'Foto kegiatan harus berupa file gambar.',
            'photo.mimes' => 'Foto kegiatan harus berformat jpeg, png, jpg, gif, atau webp.',
            'photo.max' => 'Foto kegiatan tidak boleh lebih dari 4MB.',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('photo')) {
                $slug = Str::slug($validated['title']);
                $ext = $request->file('photo')->getClientOriginalExtension();
                $fileName = $slug . '_' . time() . '.' . $ext;
                $validated['photo'] = 'storage/kegiatan/' . $fileName;
                $request->file('photo')->storeAs('kegiatan', $fileName, 'public');
            }

            KegiatanSekolah::create($validated);

            DB::commit();

            return redirect()->route('kegiatan-sekolah.index')->with('success', 'Data kegiatan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan kegiatan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        $kegiatan = KegiatanSekolah::findOrFail(BaseModel::decodeRouteKey($id));

        return view('pages.admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], [
            'title.required' => 'Judul kegiatan harus diisi.',
            'description.required' => 'Deskripsi kegiatan harus diisi.',
            'tanggal.required' => 'Tanggal kegiatan harus diisi.',
            'tanggal.date' => 'Tanggal kegiatan harus berupa tanggal valid.',
            'lokasi.required' => 'Lokasi kegiatan harus diisi.',
            'photo.image' => 'Foto kegiatan harus berupa file gambar.',
            'photo.mimes' => 'Foto kegiatan harus berformat jpeg, png, jpg, gif, atau webp.',
            'photo.max' => 'Foto kegiatan tidak boleh lebih dari 4MB.',
        ]);

        try {
            DB::beginTransaction();

            $kegiatan = KegiatanSekolah::findOrFail(BaseModel::decodeRouteKey($id));

            if ($request->hasFile('photo')) {
                if ($kegiatan->photo) {
                    Storage::delete(str_replace('storage/', '', $kegiatan->photo));
                }

                $slug = Str::slug($validated['title']);
                $ext = $request->file('photo')->getClientOriginalExtension();
                $fileName = $slug . '_' . time() . '.' . $ext;
                $validated['photo'] = 'storage/kegiatan/' . $fileName;
                $request->file('photo')->storeAs('kegiatan', $fileName, 'public');
            }

            $kegiatan->update($validated);

            DB::commit();

            return redirect()->route('kegiatan-sekolah.index')->with('success', 'Data kegiatan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui kegiatan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $kegiatan = KegiatanSekolah::findOrFail(BaseModel::decodeRouteKey($id));

            if ($kegiatan->photo) {
                Storage::delete(str_replace('storage/', '', $kegiatan->photo));
            }

            $kegiatan->delete();

            DB::commit();

            return redirect()->route('kegiatan-sekolah.index')->with('success', 'Data kegiatan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kegiatan: ' . $e->getMessage());
        }
    }
}
