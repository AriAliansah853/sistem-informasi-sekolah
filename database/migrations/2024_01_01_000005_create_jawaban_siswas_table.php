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
        Schema::create('jawaban_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('try_out_id')->constrained('try_outs')->onDelete('cascade');
            $table->foreignId('try_out_soal_id')->constrained('try_out_soals')->onDelete('cascade');
            $table->foreignId('bank_soal_id')->constrained('bank_soals')->onDelete('cascade');
            $table->text('jawaban')->nullable(); // untuk essay
            $table->foreignId('opsi_soal_id')->nullable()->constrained('opsi_soals')->onDelete('set null'); // untuk pilihan ganda
            $table->boolean('is_correct')->default(false);
            $table->integer('waktu_dikerjakan_detik')->default(0); // waktu yang dihabiskan untuk soal ini
            $table->enum('status', ['belum_dijawab', 'dijawab', 'ditinjau'])->default('belum_dijawab');
            $table->timestamp('waktu_jawab')->nullable();
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
        Schema::dropIfExists('jawaban_siswas');
    }
};
