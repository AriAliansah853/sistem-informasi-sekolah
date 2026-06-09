<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    use HasFactory;

    protected $table = 'jawaban_siswas';

    protected $fillable = [
        'siswa_id',
        'try_out_id',
        'try_out_soal_id',
        'bank_soal_id',
        'jawaban',
        'opsi_soal_id',
        'is_correct',
        'waktu_dikerjakan_detik',
        'status',
        'waktu_jawab'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'waktu_jawab' => 'datetime'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tryOut()
    {
        return $this->belongsTo(TryOut::class);
    }

    public function tryOutSoal()
    {
        return $this->belongsTo(TryOutSoal::class);
    }

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function opsiSoal()
    {
        return $this->belongsTo(OpsiSoal::class);
    }

    public function hasilTryOut()
    {
        return $this->belongsTo(HasilTryOut::class);
    }
}
