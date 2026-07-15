<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Jawaban extends BaseModel
{
    use HasFactory;

    protected $fillable = ['tugas_id', 'siswa_id', 'jawaban', 'file'];

    public function tugas() {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}

