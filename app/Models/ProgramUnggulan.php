<?php

namespace App\Models;

class ProgramUnggulan extends BaseModel
{
    protected $table = 'program_unggulans';

    protected $fillable = [
        'name',
        'description',
        'target',
    ];
}
