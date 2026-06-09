<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ppdb_years', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->index();
            $table->integer('total_applicants')->nullable();
            $table->integer('accepted_count')->nullable();
            $table->integer('enrolled_count')->nullable();
            $table->integer('new_students')->nullable();
            $table->string('source_url')->nullable();
            $table->text('summary')->nullable();
            $table->json('data_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_years');
    }
};
