<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $fillable = [
        'name',
        'logo',
        'hero_title',
        'hero_subtitle',
        'hero_cta_text',
        'hero_cta_link',
        'hero_image',
        'about_title',
        'about_description',
        'visi',
        'misi',
        'contact_address',
        'contact_phone',
        'contact_email'
    ];
=======
    protected $fillable = ['name', 'logo'];
>>>>>>> a01621e (Initial commit)
}
