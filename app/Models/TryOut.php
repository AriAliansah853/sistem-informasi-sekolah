<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TryOut extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'try_outs';

    protected $fillable = [
        'guru_id',
        'mapel_id',
        'kelas_id',
        'judul',
        'deskripsi',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
        'jumlah_soal',
        'acak_soal',
        'acak_opsi',
        'show_hasil_langsung',
        'show_pembahasan_langsung',
        'status',
        'passing_grade'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'acak_soal' => 'boolean',
        'acak_opsi' => 'boolean',
        'show_hasil_langsung' => 'boolean',
        'show_pembahasan_langsung' => 'boolean'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tryOutSoals()
    {
        return $this->hasMany(TryOutSoal::class);
    }

    public function hasilTryOuts()
    {
        return $this->hasMany(HasilTryOut::class);
    }

    public function jawabanSiswas()
    {
        return $this->hasMany(JawabanSiswa::class);
    }

    public function statistik()
    {
        return $this->hasOne(StatistikTryOut::class);
    }
}
