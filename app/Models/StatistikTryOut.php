<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikTryOut extends Model
{
    use HasFactory;

    protected $table = 'statistik_try_outs';

    protected $fillable = [
        'try_out_id',
        'jumlah_peserta',
        'jumlah_selesai',
        'jumlah_lulus',
        'jumlah_belum_lulus',
        'rata_rata_skor',
        'skor_tertinggi',
        'skor_terendah'
    ];

    public function tryOut()
    {
        return $this->belongsTo(TryOut::class);
    }
}
