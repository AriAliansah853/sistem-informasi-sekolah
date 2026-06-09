<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryOutSoal extends Model
{
    use HasFactory;

    protected $table = 'try_out_soals';

    protected $fillable = [
        'try_out_id',
        'bank_soal_id',
        'urutan',
        'bobot'
    ];

    public function tryOut()
    {
        return $this->belongsTo(TryOut::class);
    }

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function jawabanSiswas()
    {
        return $this->hasMany(JawabanSiswa::class);
    }
}
