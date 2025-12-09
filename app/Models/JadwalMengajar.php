<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalMengajar extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    /**
     * Relasi ke Penugasan Mengajar
     */
    public function penugasanMengajar(): BelongsTo
    {
        return $this->belongsTo(PenugasanMengajar::class);
    }

    /**
     * Scope untuk filter berdasarkan hari
     */
    public function scopeByHari($query, string $hari)
    {
        return $query->where('hari', $hari);
    }

    /**
     * Scope untuk jadwal hari ini
     */
    public function scopeToday($query)
    {
        $hari = $this->getNamaHariIndonesia();
        return $query->where('hari', $hari);
    }

    /**
     * Get nama hari dalam bahasa Indonesia
     */
    public static function getNamaHariIndonesia(?string $date = null): string
    {
        $timestamp = $date ? strtotime($date) : time();
        $hariInggris = date('l', $timestamp);

        $hari = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $hari[$hariInggris] ?? 'Senin';
    }

    /**
     * Accessor untuk format jam
     */
    public function getJamLengkapAttribute(): string
    {
        return $this->jam_mulai->format('H:i') . ' - ' . $this->jam_selesai->format('H:i');
    }
}
