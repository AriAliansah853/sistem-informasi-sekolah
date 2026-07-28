<?php

namespace App\Models;

class KegiatanSekolah extends BaseModel
{
    protected $table = 'kegiatan_sekolahs';

    protected $fillable = [
        'title',
        'description',
        'tanggal',
        'lokasi',
        'photo',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
