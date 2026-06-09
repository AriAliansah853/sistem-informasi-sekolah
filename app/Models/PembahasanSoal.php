<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembahasanSoal extends Model
{
    use HasFactory;

    protected $table = 'pembahasan_soals';

    protected $fillable = [
        'bank_soal_id',
        'pembahasan',
        'tips_dan_trik',
        'referensi'
    ];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }
}
