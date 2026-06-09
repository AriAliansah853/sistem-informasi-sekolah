<?php

namespace App\Services;

use App\Models\PenilaianSiswa;

class PenilaianService
{
    public function calculateFinalScore(array $scores, array $weights): float
    {
        $total = 0;
        $sumWeight = 0;

        foreach ($scores as $key => $score) {
            $value = is_numeric($score) ? floatval($score) : 0;
            $weight = isset($weights[$key]) && is_numeric($weights[$key]) ? floatval($weights[$key]) : 0;
            $total += $value * $weight / 100;
            $sumWeight += $weight;
        }

        if ($sumWeight <= 0) {
            return 0;
        }

        return round($total, 2);
    }

    public function calculateAverage(array $scores): float
    {
        $values = array_map(function ($value) {
            return is_numeric($value) ? floatval($value) : 0;
        }, $scores);

        if (count($values) === 0) {
            return 0;
        }

        return round(array_sum($values) / count($values), 2);
    }

    public function getStudentSemesterRank(int $kelasId, int $semester, string $tahun, int $siswaId): int
    {
        $averages = PenilaianSiswa::selectRaw('siswa_id, AVG(nilai_akhir) as avg_akhir')
            ->where('kelas_id', $kelasId)
            ->where('semester', $semester)
            ->where('tahun', $tahun)
            ->groupBy('siswa_id')
            ->orderByDesc('avg_akhir')
            ->get();

        $rank = 1;
        foreach ($averages as $record) {
            if ($record->siswa_id === $siswaId) {
                return $rank;
            }
            $rank++;
        }

        return 0;
    }
}
