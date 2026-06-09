<?php

namespace App\Http\Controllers;

use App\Models\PpdbYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PpdbYearController extends Controller
{
    public function index()
    {
        $ppdbYears = PpdbYear::orderBy('year', 'desc')->get();
        return view('pages.admin.ppdb-year.index', compact('ppdbYears'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'total_applicants' => 'nullable|integer|min:0',
            'accepted_count' => 'nullable|integer|min:0',
            'enrolled_count' => 'nullable|integer|min:0',
            'new_students' => 'nullable|integer|min:0',
            'source_url' => 'nullable|url',
            'summary' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $ppdb = PpdbYear::updateOrCreate(
                ['year' => $validated['year']],
                [
                    'total_applicants' => $validated['total_applicants'],
                    'accepted_count' => $validated['accepted_count'],
                    'enrolled_count' => $validated['enrolled_count'],
                    'new_students' => $validated['new_students'],
                    'source_url' => $validated['source_url'],
                    'summary' => $validated['summary'],
                ]
            );

            DB::commit();

            return redirect()->route('ppdb-year.index')->with('success', 'Data PPDB berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data PPDB: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $ppdbYear = PpdbYear::findOrFail($id);
        return view('pages.admin.ppdb-year.edit', compact('ppdbYear'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'total_applicants' => 'nullable|integer|min:0',
            'accepted_count' => 'nullable|integer|min:0',
            'enrolled_count' => 'nullable|integer|min:0',
            'new_students' => 'nullable|integer|min:0',
            'source_url' => 'nullable|url',
            'summary' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $ppdbYear = PpdbYear::findOrFail($id);
            $ppdbYear->update($validated);

            DB::commit();

            return redirect()->route('ppdb-year.index')->with('success', 'Data PPDB berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data PPDB: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $ppdbYear = PpdbYear::findOrFail($id);
            $ppdbYear->delete();

            DB::commit();

            return redirect()->route('ppdb-year.index')->with('success', 'Data PPDB berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data PPDB: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'source_url' => 'required|url',
        ]);

        try {
            $response = Http::get($validated['source_url']);

            if (!$response->successful()) {
                return redirect()->back()->with('error', 'Tidak dapat mengambil data dari URL yang diberikan.');
            }

            $payload = $response->json();
            $records = [];

            if (array_values($payload) === $payload && count($payload) > 0) {
                $records = $payload;
            } elseif (is_array($payload)) {
                $records = [$payload];
            }

            if (empty($records)) {
                return redirect()->back()->with('error', 'Format data tidak dikenali. Pastikan URL mengembalikan JSON dengan struktur yang benar.');
            }

            DB::beginTransaction();

            foreach ($records as $item) {
                if (!isset($item['year'])) {
                    continue;
                }

                PpdbYear::updateOrCreate(
                    ['year' => $item['year']],
                    [
                        'total_applicants' => $item['total_applicants'] ?? null,
                        'accepted_count' => $item['accepted_count'] ?? null,
                        'enrolled_count' => $item['enrolled_count'] ?? null,
                        'new_students' => $item['new_students'] ?? null,
                        'source_url' => $validated['source_url'],
                        'summary' => $item['summary'] ?? null,
                        'data_json' => $item,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('ppdb-year.index')->with('success', 'Data PPDB berhasil diimpor dari sumber web.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data PPDB: ' . $e->getMessage());
        }
    }
}
