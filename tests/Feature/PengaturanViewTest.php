<?php

namespace Tests\Feature;

use App\Models\Pengaturan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PengaturanViewTest extends TestCase
{
    public function test_layout_renders_when_pengaturan_is_available()
    {
        if (!Schema::hasTable('pengaturans')) {
            $this->markTestSkipped('Table pengaturans is not available.');
        }

        $pengaturan = Pengaturan::firstOrCreate([], [
            'name' => 'Sekolah Test',
        ]);

        $html = view('layouts.auth', ['pengaturan' => $pengaturan])->render();

        $this->assertStringContainsString($pengaturan->name, $html);
    }
}
