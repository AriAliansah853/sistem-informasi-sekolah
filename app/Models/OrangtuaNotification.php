<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangtuaNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'orangtua_id',
        'siswa_id',
        'title',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function orangtua()
    {
        return $this->belongsTo(Orangtua::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
