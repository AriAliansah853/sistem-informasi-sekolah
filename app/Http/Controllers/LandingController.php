<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;

class LandingController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::firstOrCreate([], [
            'name' => config('app.name'),
        ]);

        return view('landing', compact('pengaturan'));
    }
}
