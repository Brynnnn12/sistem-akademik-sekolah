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
        Schema::create('komponen_nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_mengajar_id')->constrained()->cascadeOnDelete();
            $table->string('nama'); // "UH 1"
            $table->enum('jenis', ['tugas', 'uh', 'uts', 'uas']);
            $table->integer('bobot')->default(1); // Bobot dalam persen/angka
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_nilais');
    }
};
