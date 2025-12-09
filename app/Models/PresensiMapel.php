<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresensiMapel extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
    ];

    /**
     * Relasi ke Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Relasi ke Kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi ke Mata Pelajaran
     */
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    /**
     * Relasi ke Guru (User)
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Scope untuk filter berdasarkan kelas, mapel, dan tanggal
     */
    public function scopeByKelasMapelTanggal($query, int $kelasId, int $mapelId, string $tanggal)
    {
        return $query->where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mapelId)
            ->whereDate('tanggal', $tanggal);
    }

    /**
     * Accessor untuk status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'H' => 'Hadir',
            'S' => 'Sakit',
            'I' => 'Izin',
            'A' => 'Alpha',
            'B' => 'Bolos',
            default => 'Unknown'
        };
    }
}
