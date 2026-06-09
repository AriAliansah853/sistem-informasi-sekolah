<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penilaian_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->unsignedTinyInteger('semester');
            $table->string('tahun', 9);
            $table->decimal('nilai_harian', 5, 2)->default(0);
            $table->decimal('nilai_tugas', 5, 2)->default(0);
            $table->decimal('nilai_uts', 5, 2)->default(0);
            $table->decimal('nilai_uas', 5, 2)->default(0);
            $table->decimal('nilai_sikap', 5, 2)->default(0);
            $table->decimal('nilai_kehadiran', 5, 2)->default(0);
            $table->unsignedTinyInteger('bobot_harian')->default(20);
            $table->unsignedTinyInteger('bobot_tugas')->default(20);
            $table->unsignedTinyInteger('bobot_uts')->default(25);
            $table->unsignedTinyInteger('bobot_uas')->default(25);
            $table->unsignedTinyInteger('bobot_sikap')->default(5);
            $table->unsignedTinyInteger('bobot_kehadiran')->default(5);
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->decimal('nilai_rata_rata', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['siswa_id', 'kelas_id', 'mapel_id', 'semester', 'tahun'], 'penilaian_unique_per_semester');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian_siswas');
    }
};
