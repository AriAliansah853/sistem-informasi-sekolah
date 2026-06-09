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
        Schema::create('statistik_try_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('try_out_id')->constrained('try_outs')->onDelete('cascade');
            $table->integer('jumlah_peserta')->default(0);
            $table->integer('jumlah_selesai')->default(0);
            $table->integer('jumlah_lulus')->default(0);
            $table->integer('jumlah_belum_lulus')->default(0);
            $table->decimal('rata_rata_skor', 8, 2)->default(0);
            $table->decimal('skor_tertinggi', 8, 2)->default(0);
            $table->decimal('skor_terendah', 8, 2)->default(0);
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
        Schema::dropIfExists('statistik_try_outs');
    }
};
