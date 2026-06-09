<?php

namespace App\Services;

use App\Models\JawabanSiswa;
use App\Models\HasilTryOut;
use App\Models\TryOut;
use App\Models\Siswa;
use App\Models\StatistikTryOut;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JawabanService
{
    /**
     * Simpan jawaban siswa
     */
    public function saveJawaban(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                // Check atau create hasil try out
                $hasil = HasilTryOut::firstOrCreate([
                    'siswa_id' => $data['siswa_id'],
                    'try_out_id' => $data['try_out_id']
                ], [
                    'waktu_mulai' => now(),
                    'status' => 'sedang_dikerjakan'
                ]);

                // Cek atau create jawaban
                $jawaban = JawabanSiswa::updateOrCreate([
                    'siswa_id' => $data['siswa_id'],
                    'try_out_id' => $data['try_out_id'],
                    'try_out_soal_id' => $data['try_out_soal_id'],
                    'bank_soal_id' => $data['bank_soal_id']
                ], [
                    'jawaban' => $data['jawaban'] ?? null,
                    'opsi_soal_id' => $data['opsi_soal_id'] ?? null,
                    'waktu_dikerjakan_detik' => $data['waktu_dikerjakan_detik'] ?? 0,
                    'status' => 'dijawab',
                    'waktu_jawab' => now()
                ]);

                // Update status hasil
                $this->updateHasilStatus($hasil);

                return $jawaban;
            });
        } catch (Exception $e) {
            throw new Exception('Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    /**
     * Update status hasil try out
     */
    public function updateHasilStatus(HasilTryOut $hasil)
    {
        $jawabanCount = JawabanSiswa::where('siswa_id', $hasil->siswa_id)
            ->where('try_out_id', $hasil->try_out_id)
            ->where('status', 'dijawab')
            ->count();

        $hasil->update([
            'jumlah_dijawab' => $jawabanCount
        ]);
    }

    /**
     * Selesaikan try out dan hitung skor
     */
    public function finalizeTryOut($siswaId, $tryOutId)
    {
        try {
            return DB::transaction(function () use ($siswaId, $tryOutId) {
                $hasil = HasilTryOut::where('siswa_id', $siswaId)
                    ->where('try_out_id', $tryOutId)
                    ->firstOrFail();

                $tryOut = TryOut::findOrFail($tryOutId);
                $jawabanSiswas = JawabanSiswa::where('siswa_id', $siswaId)
                    ->where('try_out_id', $tryOutId)
                    ->get();

                // Calculate scores
                $jumlahBenar = 0;
                $skorMentah = 0;
                $totalBobot = 0;

                foreach ($jawabanSiswas as $jawaban) {
                    $tryOutSoal = $jawaban->tryOutSoal;
                    $bankSoal = $jawaban->bankSoal;
                    $bobot = $tryOutSoal->bobot ?? 1;
                    $totalBobot += $bobot;

                    // Check if answer is correct
                    if ($this->isAnswerCorrect($jawaban, $bankSoal)) {
                        $jumlahBenar++;
                        $skorMentah += $bobot;
                        $jawaban->update(['is_correct' => true]);
                    }
                }

                $jumlahSalah = $jawabanSiswas->where('status', 'dijawab')->count() - $jumlahBenar;
                $jumlahKosong = $tryOut->jumlah_soal - $jawabanSiswas->where('status', 'dijawab')->count();

                // Calculate final score (0-100)
                $skorAkhir = ($totalBobot > 0) ? ($skorMentah / $totalBobot) * 100 : 0;

                // Determine pass/fail
                $statusKelulusan = $skorAkhir >= $tryOut->passing_grade ? 'lulus' : 'belum_lulus';

                // Get nilai huruf
                $nilaiHuruf = $this->getNilaiHuruf($skorAkhir);

                // Update hasil
                $hasil->update([
                    'waktu_selesai' => now(),
                    'durasi_pengerjaan_detik' => now()->diffInSeconds($hasil->waktu_mulai),
                    'jumlah_dijawab' => $jawabanSiswas->where('status', 'dijawab')->count(),
                    'jumlah_benar' => $jumlahBenar,
                    'jumlah_salah' => $jumlahSalah,
                    'jumlah_kosong' => $jumlahKosong,
                    'skor_mentah' => round($skorMentah, 2),
                    'skor_akhir' => round($skorAkhir, 2),
                    'nilai_huruf' => $nilaiHuruf,
                    'status_kelulusan' => $statusKelulusan,
                    'status' => 'selesai'
                ]);

                // Update statistik
                $this->updateStatistik($tryOutId);

                return $hasil->fresh();
            });
        } catch (Exception $e) {
            throw new Exception('Gagal menyelesaikan try out: ' . $e->getMessage());
        }
    }

    /**
     * Check if jawaban benar
     */
    private function isAnswerCorrect(JawabanSiswa $jawaban, $bankSoal)
    {
        if ($bankSoal->tipe === 'pilihan_ganda') {
            if ($jawaban->opsi_soal_id) {
                return $jawaban->opsiSoal->is_correct;
            }
        } elseif ($bankSoal->tipe === 'benar_salah') {
            return $jawaban->jawaban === $this->getKunciJawaban($bankSoal);
        } elseif ($bankSoal->tipe === 'essay') {
            // Essay akan dikoreksi manual, return false untuk sementara
            return false;
        }

        return false;
    }

    /**
     * Get kunci jawaban soal
     */
    private function getKunciJawaban($bankSoal)
    {
        $opsiBenar = $bankSoal->opsiSoal()->where('is_correct', true)->first();
        return $opsiBenar ? $opsiBenar->kode : null;
    }

    /**
     * Get nilai huruf berdasarkan skor
     */
    private function getNilaiHuruf($skor)
    {
        if ($skor >= 85) return 'A';
        if ($skor >= 75) return 'B';
        if ($skor >= 65) return 'C';
        if ($skor >= 55) return 'D';
        return 'E';
    }

    /**
     * Update statistik try out
     */
    private function updateStatistik($tryOutId)
    {
        $hasilCount = HasilTryOut::where('try_out_id', $tryOutId)->count();
        $selesaiCount = HasilTryOut::where('try_out_id', $tryOutId)
            ->where('status', 'selesai')
            ->count();
        $lulusCount = HasilTryOut::where('try_out_id', $tryOutId)
            ->where('status_kelulusan', 'lulus')
            ->count();
        $belumLulusCount = $selesaiCount - $lulusCount;

        $rataRataSkor = HasilTryOut::where('try_out_id', $tryOutId)
            ->where('status', 'selesai')
            ->avg('skor_akhir') ?? 0;

        $skorTertinggi = HasilTryOut::where('try_out_id', $tryOutId)
            ->where('status', 'selesai')
            ->max('skor_akhir') ?? 0;

        $skorTerendah = HasilTryOut::where('try_out_id', $tryOutId)
            ->where('status', 'selesai')
            ->min('skor_akhir') ?? 0;

        StatistikTryOut::where('try_out_id', $tryOutId)->update([
            'jumlah_peserta' => $hasilCount,
            'jumlah_selesai' => $selesaiCount,
            'jumlah_lulus' => $lulusCount,
            'jumlah_belum_lulus' => $belumLulusCount,
            'rata_rata_skor' => round($rataRataSkor, 2),
            'skor_tertinggi' => round($skorTertinggi, 2),
            'skor_terendah' => round($skorTerendah, 2)
        ]);
    }

    /**
     * Get ranking siswa
     */
    public function getRankingSiswa($tryOutId)
    {
        return HasilTryOut::where('try_out_id', $tryOutId)
            ->where('status', 'selesai')
            ->with('siswa')
            ->orderByDesc('skor_akhir')
            ->get()
            ->map(function ($hasil, $index) {
                $hasil->ranking = $index + 1;
                return $hasil;
            });
    }

    /**
     * Hitung ulang skor jawaban essay
     */
    public function scoreEssayAnswer(JawabanSiswa $jawaban, $skor)
    {
        try {
            $jawaban->update([
                'is_correct' => $skor > 0,
                'status' => 'dikoreksi'
            ]);

            // Update hasil
            $hasil = HasilTryOut::where('siswa_id', $jawaban->siswa_id)
                ->where('try_out_id', $jawaban->try_out_id)
                ->first();

            if ($hasil) {
                $this->finalizeTryOut($jawaban->siswa_id, $jawaban->try_out_id);
            }

            return $jawaban;
        } catch (Exception $e) {
            throw new Exception('Gagal menilai jawaban essay: ' . $e->getMessage());
        }
    }
}
