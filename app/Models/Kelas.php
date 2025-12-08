<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    /** @use HasFactory<\Database\Factories\KelasFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'tingkat_kelas',
        'wali_kelas_id',
    ];

    protected $casts = [
        'tingkat_kelas' => 'integer',
        'wali_kelas_id' => 'integer',
    ];

    // Relationships
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'kelas_siswas')
            ->withPivot('tahun_ajaran_id')
            ->withTimestamps();
    }

    public function penugasanMengajar()
    {
        return $this->hasMany(PenugasanMengajar::class, 'kelas_id');
    }


    // Scopes
    public function scopeByTingkat($query, $tingkat)
    {
        return $query->where('tingkat_kelas', $tingkat);
    }

    public function scopeByWaliKelas($query, $waliKelasId)
    {
        return $query->where('wali_kelas_id', $waliKelasId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
                ->orWhere('tingkat_kelas', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getNamaLengkapAttribute(): string
    {
        return "Kelas {$this->nama} (Tingkat {$this->tingkat_kelas})";
    }

    public function getJumlahSiswaAttribute(): int
    {
        return $this->siswas()->count();
    }

    public function getJumlahSiswaAktifAttribute(): int
    {
        $tahunAjaranAktif = \App\Models\TahunAjaran::aktif()->first();
        if (!$tahunAjaranAktif) {
            return 0;
        }

        return $this->siswas()
            ->wherePivot('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->count();
    }

    public function getTingkatRomawiAttribute(): string
    {
        $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI'];
        return $romawi[$this->tingkat_kelas] ?? $this->tingkat_kelas;
    }
}
