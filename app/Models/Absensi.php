<?php

namespace App\Models;

class Absensi extends BaseModel
{
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'status',
        'keterangan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
