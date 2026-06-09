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
        Schema::create('bank_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->string('judul');
            $table->text('isi');
            $table->enum('tipe', ['pilihan_ganda', 'essay', 'benar_salah'])->default('pilihan_ganda');
            $table->integer('tingkat_kesulitan')->default(1); // 1-5
            $table->text('pembahasan')->nullable();
            $table->string('kata_kunci')->nullable();
            $table->integer('bobot')->default(1);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_soals');
    }
};
