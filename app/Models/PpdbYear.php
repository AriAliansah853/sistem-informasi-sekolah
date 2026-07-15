<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class PpdbYear extends BaseModel
{
    use HasFactory;

    protected $table = 'ppdb_years';

    protected $fillable = [
        'year',
        'total_applicants',
        'accepted_count',
        'enrolled_count',
        'new_students',
        'source_url',
        'summary',
        'data_json',
    ];

    protected $casts = [
        'data_json' => 'array',
    ];
}

