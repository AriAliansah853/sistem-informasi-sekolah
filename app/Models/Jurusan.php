<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Jurusan extends BaseModel
{
    use HasFactory;
    protected $table = 'jurusans';

    protected $fillable = [
        'nama_jurusan',
    ];

    public function mapel()
    {
        return $this->hasMany(Mapel::class);
    }
}

