<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\ProgramUnggulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramUnggulanController extends Controller
{
    public function index()
    {
        $programs = ProgramUnggulan::orderBy('id', 'desc')->get();

        return view('pages.admin.program-unggulan.index', compact('programs'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama program unggulan harus diisi.',
            'description.required' => 'Deskripsi program unggulan harus diisi.',
        ]);

        try {
            DB::beginTransaction();

            ProgramUnggulan::create($validated);

            DB::commit();

            return redirect()->route('program-unggulan.index')->with('success', 'Program unggulan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan program unggulan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        $program = ProgramUnggulan::findOrFail(BaseModel::decodeRouteKey($id));

        return view('pages.admin.program-unggulan.edit', compact('program'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama program unggulan harus diisi.',
            'description.required' => 'Deskripsi program unggulan harus diisi.',
        ]);

        try {
            DB::beginTransaction();

            $program = ProgramUnggulan::findOrFail(BaseModel::decodeRouteKey($id));
            $program->update($validated);

            DB::commit();

            return redirect()->route('program-unggulan.index')->with('success', 'Program unggulan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui program unggulan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $program = ProgramUnggulan::findOrFail(BaseModel::decodeRouteKey($id));
            $program->delete();

            DB::commit();

            return redirect()->route('program-unggulan.index')->with('success', 'Program unggulan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus program unggulan: ' . $e->getMessage());
        }
    }
}
