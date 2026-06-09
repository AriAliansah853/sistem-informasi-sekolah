<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankSoal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bank_soals';

    protected $fillable = [
        'guru_id',
        'mapel_id',
        'judul',
        'isi',
        'tipe',
        'tingkat_kesulitan',
        'pembahasan',
        'kata_kunci',
        'bobot',
        'status'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function opsiSoal()
    {
        return $this->hasMany(OpsiSoal::class);
    }

    public function pembahasan()
    {
        return $this->hasOne(PembahasanSoal::class);
    }

    public function tryOutSoals()
    {
        return $this->hasMany(TryOutSoal::class);
    }

    public function jawabanSiswas()
    {
        return $this->hasMany(JawabanSiswa::class);
    }
}
