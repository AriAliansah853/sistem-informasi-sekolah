<?php

namespace App\Services;

use App\Models\BankSoal;
use App\Models\OpsiSoal;
use Exception;
use Illuminate\Support\Facades\DB;

class BankSoalService
{
    /**
     * Membuat soal baru
     */
    public function createSoal(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $soal = BankSoal::create([
                    'guru_id' => $data['guru_id'],
                    'mapel_id' => $data['mapel_id'],
                    'judul' => $data['judul'],
                    'isi' => $data['isi'],
                    'tipe' => $data['tipe'],
                    'tingkat_kesulitan' => $data['tingkat_kesulitan'] ?? 1,
                    'pembahasan' => $data['pembahasan'] ?? null,
                    'kata_kunci' => $data['kata_kunci'] ?? null,
                    'bobot' => $data['bobot'] ?? 1,
                    'status' => $data['status'] ?? 'draft'
                ]);

                // Jika ada opsi soal, create juga
                if (isset($data['opsi_soal']) && !empty($data['opsi_soal'])) {
                    foreach ($data['opsi_soal'] as $index => $opsi) {
                        OpsiSoal::create([
                            'bank_soal_id' => $soal->id,
                            'kode' => $opsi['kode'],
                            'teks' => $opsi['teks'],
                            'is_correct' => $opsi['is_correct'] ?? false,
                            'urutan' => $index + 1
                        ]);
                    }
                }

                return $soal->load('opsiSoal');
            });
        } catch (Exception $e) {
            throw new Exception('Gagal membuat soal: ' . $e->getMessage());
        }
    }

    /**
     * Update soal
     */
    public function updateSoal(BankSoal $soal, array $data)
    {
        try {
            return DB::transaction(function () use ($soal, $data) {
                $soal->update([
                    'judul' => $data['judul'] ?? $soal->judul,
                    'isi' => $data['isi'] ?? $soal->isi,
                    'tipe' => $data['tipe'] ?? $soal->tipe,
                    'tingkat_kesulitan' => $data['tingkat_kesulitan'] ?? $soal->tingkat_kesulitan,
                    'pembahasan' => $data['pembahasan'] ?? $soal->pembahasan,
                    'kata_kunci' => $data['kata_kunci'] ?? $soal->kata_kunci,
                    'bobot' => $data['bobot'] ?? $soal->bobot,
                    'status' => $data['status'] ?? $soal->status
                ]);

                // Update opsi soal jika ada
                if (isset($data['opsi_soal'])) {
                    $soal->opsiSoal()->delete();
                    foreach ($data['opsi_soal'] as $index => $opsi) {
                        OpsiSoal::create([
                            'bank_soal_id' => $soal->id,
                            'kode' => $opsi['kode'],
                            'teks' => $opsi['teks'],
                            'is_correct' => $opsi['is_correct'] ?? false,
                            'urutan' => $index + 1
                        ]);
                    }
                }

                return $soal->fresh()->load('opsiSoal');
            });
        } catch (Exception $e) {
            throw new Exception('Gagal update soal: ' . $e->getMessage());
        }
    }

    /**
     * Hapus soal
     */
    public function deleteSoal(BankSoal $soal)
    {
        try {
            return $soal->delete();
        } catch (Exception $e) {
            throw new Exception('Gagal menghapus soal: ' . $e->getMessage());
        }
    }

    /**
     * Get soal dengan opsi
     */
    public function getSoal(BankSoal $soal)
    {
        return $soal->load('opsiSoal', 'pembahasan', 'guru', 'mapel');
    }

    /**
     * Get semua soal guru dengan filter
     */
    public function getSoalByGuru($guruId, $mapelId = null, $status = 'published')
    {
        $query = BankSoal::where('guru_id', $guruId);

        if ($mapelId) {
            $query->where('mapel_id', $mapelId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->with('opsiSoal', 'mapel')->get();
    }

    /**
     * Get soal random untuk try out
     */
    public function getSoalRandom($mapelId, $jumlah = 10, $excludeSoalIds = [])
    {
        $query = BankSoal::where('mapel_id', $mapelId)
            ->where('status', 'published');

        if (!empty($excludeSoalIds)) {
            $query->whereNotIn('id', $excludeSoalIds);
        }

        return $query->inRandomOrder()
            ->limit($jumlah)
            ->with('opsiSoal')
            ->get();
    }

    /**
     * Publish soal
     */
    public function publishSoal(BankSoal $soal)
    {
        return $soal->update(['status' => 'published']);
    }

    /**
     * Unpublish soal
     */
    public function unpublishSoal(BankSoal $soal)
    {
        return $soal->update(['status' => 'draft']);
    }
}
