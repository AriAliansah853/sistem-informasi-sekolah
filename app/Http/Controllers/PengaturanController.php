<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
<<<<<<< HEAD
        $pengaturan = Pengaturan::firstOrCreate([], [
            'name' => config('app.name'),
        ]);
=======
        $pengaturan = Pengaturan::first();
>>>>>>> a01621e (Initial commit)
        return view('pages.admin.pengaturan.index', compact('pengaturan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
<<<<<<< HEAD
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'hero_cta_text' => 'nullable|string|max:100',
            'hero_cta_link' => 'nullable|url',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'about_title' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
=======
>>>>>>> a01621e (Initial commit)
        ], [
            'nama_sekolah.required' => 'Nama sekolah harus diisi.',
            'nama_sekolah.string' => 'Nama sekolah harus berupa teks.',
            'nama_sekolah.max' => 'Nama sekolah tidak boleh lebih dari 255 karakter.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat jpeg, png, jpg, atau gif.',
            'logo.max' => 'Logo tidak boleh lebih dari 2MB.',
<<<<<<< HEAD
            'hero_image.image' => 'Gambar hero harus berupa file gambar.',
            'hero_image.mimes' => 'Gambar hero harus berformat jpeg, png, jpg, atau gif.',
            'hero_image.max' => 'Gambar hero tidak boleh lebih dari 4MB.',
            'hero_cta_link.url' => 'Link CTA harus berupa URL yang valid.',
            'contact_email.email' => 'Email kontak harus berupa alamat email yang valid.',
=======
>>>>>>> a01621e (Initial commit)
        ]);

        $pengaturan = Pengaturan::findOrFail($id);
        $pengaturan->name = $validatedData['nama_sekolah'];
<<<<<<< HEAD
        $pengaturan->hero_title = $validatedData['hero_title'] ?? null;
        $pengaturan->hero_subtitle = $validatedData['hero_subtitle'] ?? null;
        $pengaturan->hero_cta_text = $validatedData['hero_cta_text'] ?? null;
        $pengaturan->hero_cta_link = $validatedData['hero_cta_link'] ?? null;
        $pengaturan->about_title = $validatedData['about_title'] ?? null;
        $pengaturan->about_description = $validatedData['about_description'] ?? null;
        $pengaturan->visi = $validatedData['visi'] ?? null;
        $pengaturan->misi = $validatedData['misi'] ?? null;
        $pengaturan->contact_address = $validatedData['contact_address'] ?? null;
        $pengaturan->contact_phone = $validatedData['contact_phone'] ?? null;
        $pengaturan->contact_email = $validatedData['contact_email'] ?? null;

        if ($request->hasFile('logo')) {
            if ($pengaturan->logo) {
                Storage::delete($pengaturan->logo);
            }
=======

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($pengaturan->logo) {
                Storage::delete($pengaturan->logo);
            }
            // Simpan logo baru dengan nama sesuai nama sekolah
>>>>>>> a01621e (Initial commit)
            $slug = Str::slug($pengaturan->name);
            $pengaturan->logo = 'storage/logos/' . $slug . '_logo.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->storeAs('logos', $slug . '_logo.' . $request->file('logo')->getClientOriginalExtension(), 'public');
        }

<<<<<<< HEAD
        if ($request->hasFile('hero_image')) {
            if ($pengaturan->hero_image) {
                Storage::delete($pengaturan->hero_image);
            }
            $slug = Str::slug($pengaturan->name . '-hero');
            $pengaturan->hero_image = 'storage/landing/' . $slug . '_hero.' . $request->file('hero_image')->getClientOriginalExtension();
            $request->file('hero_image')->storeAs('landing', $slug . '_hero.' . $request->file('hero_image')->getClientOriginalExtension(), 'public');
        }

=======
>>>>>>> a01621e (Initial commit)
        $pengaturan->save();

        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(404);
    }
}
