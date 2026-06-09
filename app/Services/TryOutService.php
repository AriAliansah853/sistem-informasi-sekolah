<?php

namespace App\Services;

use App\Models\TryOut;
use App\Models\TryOutSoal;
use App\Models\BankSoal;
use App\Models\HasilTryOut;
use App\Models\JawabanSiswa;
use App\Models\StatistikTryOut;
use Exception;
use Illuminate\Support\Facades\DB;

class TryOutService
{
    /**
     * Membuat try out baru
     */
    public function createTryOut(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $tryOut = TryOut::create([
                    'guru_id' => $data['guru_id'],
                    'mapel_id' => $data['mapel_id'],
                    'kelas_id' => $data['kelas_id'] ?? null,
                    'judul' => $data['judul'],
                    'deskripsi' => $data['deskripsi'] ?? null,
                    'waktu_mulai' => $data['waktu_mulai'],
                    'waktu_selesai' => $data['waktu_selesai'],
                    'durasi_menit' => $data['durasi_menit'],
                    'jumlah_soal' => $data['jumlah_soal'] ?? 0,
                    'acak_soal' => $data['acak_soal'] ?? true,
                    'acak_opsi' => $data['acak_opsi'] ?? true,
                    'show_hasil_langsung' => $data['show_hasil_langsung'] ?? false,
                    'show_pembahasan_langsung' => $data['show_pembahasan_langsung'] ?? false,
                    'status' => $data['status'] ?? 'draft',
                    'passing_grade' => $data['passing_grade'] ?? 60
                ]);

                // Tambah soal ke try out jika ada
                if (isset($data['soal_ids']) && !empty($data['soal_ids'])) {
                    $this->addSoalsToTryOut($tryOut, $data['soal_ids']);
                }

                // Create statistik
                StatistikTryOut::create(['try_out_id' => $tryOut->id]);

                return $tryOut->load('tryOutSoals', 'statistik');
            });
        } catch (Exception $e) {
            throw new Exception('Gagal membuat try out: ' . $e->getMessage());
        }
    }

    /**
     * Tambah soal ke try out
     */
    public function addSoalsToTryOut(TryOut $tryOut, array $soalIds)
    {
        try {
            return DB::transaction(function () use ($tryOut, $soalIds) {
                $urutan = $tryOut->tryOutSoals()->max('urutan') ?? 0;

                foreach ($soalIds as $soalId) {
                    if (BankSoal::where('id', $soalId)->where('status', 'published')->exists()) {
                        $urutan++;
                        TryOutSoal::create([
                            'try_out_id' => $tryOut->id,
                            'bank_soal_id' => $soalId,
                            'urutan' => $urutan,
                            'bobot' => 1
                        ]);
                    }
                }

                // Update jumlah soal
                $tryOut->update(['jumlah_soal' => $tryOut->tryOutSoals()->count()]);

                return $tryOut;
            });
        } catch (Exception $e) {
            throw new Exception('Gagal menambah soal: ' . $e->getMessage());
        }
    }

    /**
     * Update try out
     */
    public function updateTryOut(TryOut $tryOut, array $data)
    {
        try {
            return DB::transaction(function () use ($tryOut, $data) {
                $tryOut->update([
                    'judul' => $data['judul'] ?? $tryOut->judul,
                    'deskripsi' => $data['deskripsi'] ?? $tryOut->deskripsi,
                    'waktu_mulai' => $data['waktu_mulai'] ?? $tryOut->waktu_mulai,
                    'waktu_selesai' => $data['waktu_selesai'] ?? $tryOut->waktu_selesai,
                    'durasi_menit' => $data['durasi_menit'] ?? $tryOut->durasi_menit,
                    'acak_soal' => $data['acak_soal'] ?? $tryOut->acak_soal,
                    'acak_opsi' => $data['acak_opsi'] ?? $tryOut->acak_opsi,
                    'show_hasil_langsung' => $data['show_hasil_langsung'] ?? $tryOut->show_hasil_langsung,
                    'show_pembahasan_langsung' => $data['show_pembahasan_langsung'] ?? $tryOut->show_pembahasan_langsung,
                    'status' => $data['status'] ?? $tryOut->status,
                    'passing_grade' => $data['passing_grade'] ?? $tryOut->passing_grade
                ]);

                return $tryOut;
            });
        } catch (Exception $e) {
            throw new Exception('Gagal update try out: ' . $e->getMessage());
        }
    }

    /**
     * Hapus try out
     */
    public function deleteTryOut(TryOut $tryOut)
    {
        try {
            return $tryOut->delete();
        } catch (Exception $e) {
            throw new Exception('Gagal menghapus try out: ' . $e->getMessage());
        }
    }

    /**
     * Get try out dengan soal
     */
    public function getTryOut(TryOut $tryOut, $acakSoal = false)
    {
        $tryOut = $tryOut->load('tryOutSoals.bankSoal.opsiSoal', 'statistik');

        // Acak soal jika diperlukan
        if ($acakSoal && $tryOut->acak_soal) {
            $soals = $tryOut->tryOutSoals->shuffle();
            $tryOut->setRelation('tryOutSoals', $soals);
        }

        return $tryOut;
    }

    /**
     * Get soal try out untuk siswa dengan acak
     */
    public function getSoalForSiswa(TryOut $tryOut)
    {
        $soals = $tryOut->tryOutSoals()
            ->with('bankSoal')
            ->orderBy('urutan')
            ->get()
            ->map(function ($tryOutSoal) use ($tryOut) {
                $soal = $tryOutSoal->bankSoal;
                
                // Acak opsi jika diperlukan
                if ($tryOut->acak_opsi && $soal->tipe === 'pilihan_ganda') {
                    $opsi = $soal->opsiSoal->shuffle();
                    $soal->setRelation('opsiSoal', $opsi);
                }

                return [
                    'try_out_soal_id' => $tryOutSoal->id,
                    'bank_soal_id' => $soal->id,
                    'urutan' => $tryOutSoal->urutan,
                    'soal' => $soal->only(['id', 'judul', 'isi', 'tipe']),
                    'opsi' => $soal->tipe === 'pilihan_ganda' 
                        ? $soal->opsiSoal->map(fn($o) => ['id' => $o->id, 'kode' => $o->kode, 'teks' => $o->teks])
                        : null
                ];
            });

        return $soals;
    }

    /**
     * Publish try out
     */
    public function publishTryOut(TryOut $tryOut)
    {
        if ($tryOut->tryOutSoals()->count() === 0) {
            throw new Exception('Try out harus memiliki minimal 1 soal');
        }

        return $tryOut->update(['status' => 'active']);
    }

    /**
     * Close try out
     */
    public function closeTryOut(TryOut $tryOut)
    {
        return $tryOut->update(['status' => 'selesai']);
    }
}
