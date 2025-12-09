<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiSiswa extends Model
{
    /** @use HasFactory<\Database\Factories\NilaiSiswaFactory> */
    use HasFactory;

    protected $fillable = [
        'komponen_nilai_id',
        'siswa_id',
        'nilai',
    ];

    protected $casts = [
        'komponen_nilai_id' => 'integer',
        'siswa_id' => 'integer',
        'nilai' => 'float',
    ];

    // Relationships
    public function komponenNilai(): BelongsTo
    {
        return $this->belongsTo(KomponenNilai::class, 'komponen_nilai_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
