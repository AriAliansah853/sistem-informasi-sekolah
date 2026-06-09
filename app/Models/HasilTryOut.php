<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilTryOut extends Model
{
    use HasFactory;

    protected $table = 'hasil_try_outs';

    protected $fillable = [
        'siswa_id',
        'try_out_id',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_pengerjaan_detik',
        'jumlah_dijawab',
        'jumlah_benar',
        'jumlah_salah',
        'jumlah_kosong',
        'skor_mentah',
        'skor_akhir',
        'nilai_huruf',
        'status_kelulusan',
        'status',
        'catatan_guru'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tryOut()
    {
        return $this->belongsTo(TryOut::class);
    }

    public function jawabanSiswas()
    {
        return $this->hasMany(JawabanSiswa::class);
    }
}
