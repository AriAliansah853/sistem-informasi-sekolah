<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Mapel extends BaseModel
{
    use HasFactory;

    protected $fillable = ['nama_mapel', 'jurusan_id'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}

