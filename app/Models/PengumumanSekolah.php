<?php

namespace App\Models;

class PengumumanSekolah extends BaseModel
{
    protected $fillable = ['start_at', 'end_at', 'description'];

    protected $cast = [
        'start_at' => 'date',
        'end_at' => 'date'
    ];

    public function scopeActive($query)
    {
        return $query->where('start_at', '<=', now())
            ->where('end_at', '>=', now());
    }
}

