<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Guru extends BaseModel
{
    use HasFactory;

    protected $fillable = ['nip', 'nama', 'mapel_id', 'no_telp', 'alamat', 'foto'];

    public function mapel() {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}

