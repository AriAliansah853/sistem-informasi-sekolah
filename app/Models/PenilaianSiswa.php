<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianSiswa extends Model
{
    use HasFactory;

    protected $table = 'penilaian_siswas';

    protected $fillable = [
        'guru_id',
        'siswa_id',
        'kelas_id',
        'mapel_id',
        'semester',
        'tahun',
        'nilai_harian',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_sikap',
        'nilai_kehadiran',
        'bobot_harian',
        'bobot_tugas',
        'bobot_uts',
        'bobot_uas',
        'bobot_sikap',
        'bobot_kehadiran',
        'nilai_akhir',
        'nilai_rata_rata',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }
}
