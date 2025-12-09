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
        Schema::table('nilai_akhirs', function (Blueprint $table) {
            // Pastikan kolom yang diperlukan ada
            if (!Schema::hasColumn('nilai_akhirs', 'mata_pelajaran_id')) {
                $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
            }

            if (!Schema::hasColumn('nilai_akhirs', 'tahun_ajaran_id')) {
                $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            }

            if (!Schema::hasColumn('nilai_akhirs', 'siswa_id')) {
                $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            }

            // Hapus kolom lama jika ada
            if (Schema::hasColumn('nilai_akhirs', 'nilai_pengetahuan')) {
                $table->dropColumn('nilai_pengetahuan');
            }

            if (Schema::hasColumn('nilai_akhirs', 'nilai_keterampilan')) {
                $table->dropColumn('nilai_keterampilan');
            }

            if (Schema::hasColumn('nilai_akhirs', 'predikat')) {
                $table->dropColumn('predikat');
            }

            if (Schema::hasColumn('nilai_akhirs', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }

            // Tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('nilai_akhirs', 'nilai_akhir')) {
                $table->decimal('nilai_akhir', 5, 2)->after('siswa_id');
            }

            if (!Schema::hasColumn('nilai_akhirs', 'grade')) {
                $table->string('grade', 1)->after('nilai_akhir');
            }

            // Pastikan unique constraint ada (hanya jika belum ada)
            // Note: Constraint sudah ada dari migration awal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_akhirs', function (Blueprint $table) {
            // Drop kolom baru
            if (Schema::hasColumn('nilai_akhirs', 'nilai_akhir')) {
                $table->dropColumn('nilai_akhir');
            }

            if (Schema::hasColumn('nilai_akhirs', 'grade')) {
                $table->dropColumn('grade');
            }

            // Add back kolom lama
            if (!Schema::hasColumn('nilai_akhirs', 'nilai_pengetahuan')) {
                $table->float('nilai_pengetahuan');
            }

            if (!Schema::hasColumn('nilai_akhirs', 'nilai_keterampilan')) {
                $table->float('nilai_keterampilan')->nullable();
            }

            if (!Schema::hasColumn('nilai_akhirs', 'predikat')) {
                $table->string('predikat')->nullable();
            }

            if (!Schema::hasColumn('nilai_akhirs', 'deskripsi')) {
                $table->text('deskripsi')->nullable();
            }
        });
    }
};
