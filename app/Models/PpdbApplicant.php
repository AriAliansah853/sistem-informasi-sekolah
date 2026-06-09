<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbApplicant extends Model
{
    use HasFactory;

    protected $table = 'ppdb_applicants';

    protected $fillable = [
        'nama_lengkap',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telp',
        'email',
        'sekolah_asal',
        'jurusan_pilihan',
        'nama_ortu',
        'telp_ortu',
        'keterangan',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}
