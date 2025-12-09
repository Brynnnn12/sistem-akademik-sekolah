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
        Schema::create('presensi_mapels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai')->nullable();
            $table->enum('status', ['H', 'S', 'I', 'A', 'B'])->default('H');
            $table->text('materi')->nullable();
            $table->string('catatan')->nullable();
            $table->timestamps();

            // Unique constraint: Prevent duplicate attendance for same student, subject, date, and time
            $table->unique(['siswa_id', 'mata_pelajaran_id', 'tanggal', 'jam_mulai'], 'unique_presensi_mapel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_mapels');
    }
};
