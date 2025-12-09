<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomponenNilai extends Model
{
    /** @use HasFactory<\Database\Factories\KomponenNilaiFactory> */
    use HasFactory;

    protected $fillable = [
        'penugasan_mengajar_id',
        'nama',
        'jenis',
        'bobot',
    ];

    protected $casts = [
        'penugasan_mengajar_id' => 'integer',
        'bobot' => 'integer',
    ];

    // Relationships
    public function penugasan_mengajar(): BelongsTo
    {
        return $this->belongsTo(PenugasanMengajar::class, 'penugasan_mengajar_id');
    }

    public function nilaiSiswas()
    {
        return $this->hasMany(NilaiSiswa::class, 'komponen_nilai_id');
    }
}
