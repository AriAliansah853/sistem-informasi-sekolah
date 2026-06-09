<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiSoal extends Model
{
    use HasFactory;

    protected $table = 'opsi_soals';

    protected $fillable = [
        'bank_soal_id',
        'kode',
        'teks',
        'is_correct',
        'urutan'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function jawabanSiswas()
    {
        return $this->hasMany(JawabanSiswa::class);
    }
}
