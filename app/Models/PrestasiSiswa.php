<?php

namespace App\Models;

class PrestasiSiswa extends BaseModel
{
    protected $table = 'prestasi_siswas';

    protected $fillable = [
        'nama_siswa',
        'kelas',
        'jenis_prestasi',
        'tahun',
        'deskripsi',
    ];
}
