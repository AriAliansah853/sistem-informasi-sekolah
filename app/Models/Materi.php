<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Materi extends BaseModel
{
    use HasFactory;
    protected $fillable = ['judul', 'deskripsi', 'file', 'guru_id', 'kelas_id'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}

