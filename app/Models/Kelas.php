<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Kelas extends BaseModel
{
    use HasFactory;

    protected $fillable = ['nama_kelas', 'guru_id'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}

