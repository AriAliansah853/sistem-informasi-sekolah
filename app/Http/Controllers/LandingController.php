<?php

namespace App\Http\Controllers;

use App\Models\KegiatanSekolah;
use App\Models\Pengaturan;
use App\Models\PrestasiSiswa;
use App\Models\ProgramUnggulan;

class LandingController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::firstOrCreate([], [
            'name' => config('app.name'),
        ]);

        // return $pengaturan;

        $programUnggulan = ProgramUnggulan::orderBy('id', 'desc')->get();
        $prestasiSiswa = PrestasiSiswa::orderBy('tahun', 'desc')->orderBy('id', 'desc')->get();
        $kegiatanSekolah = KegiatanSekolah::orderBy('tanggal', 'desc')->get();

        return view('landing', compact('pengaturan', 'programUnggulan', 'prestasiSiswa', 'kegiatanSekolah'));
    }
}


