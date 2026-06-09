<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hasil_try_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('try_out_id')->constrained('try_outs')->onDelete('cascade');
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('durasi_pengerjaan_detik')->default(0);
            $table->integer('jumlah_dijawab')->default(0);
            $table->integer('jumlah_benar')->default(0);
            $table->integer('jumlah_salah')->default(0);
            $table->integer('jumlah_kosong')->default(0);
            $table->decimal('skor_mentah', 8, 2)->default(0);
            $table->decimal('skor_akhir', 8, 2)->default(0);
            $table->integer('nilai_huruf')->nullable();
            $table->string('status_kelulusan')->default('belum_lulus'); // lulus, belum_lulus
            $table->enum('status', ['sedang_dikerjakan', 'selesai', 'dikoreksi'])->default('sedang_dikerjakan');
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hasil_try_outs');
    }
};
