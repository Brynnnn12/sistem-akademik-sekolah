<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenugasanMengajar extends Model
{
    /** @use HasFactory<\Database\Factories\PenugasanMengajarFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mata_pelajaran_id',
        'tahun_ajaran_id',
    ];

    protected $casts = [
        'guru_id' => 'integer',
        'kelas_id' => 'integer',
        'mata_pelajaran_id' => 'integer',
        'tahun_ajaran_id' => 'integer',
    ];

    // Relationships
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    // Scopes
    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByMataPelajaran($query, $mataPelajaranId)
    {
        return $query->where('mata_pelajaran_id', $mataPelajaranId);
    }

    public function scopeByTahunAjaran($query, $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    public function scopeAktif($query)
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();
        return $tahunAjaranAktif ? $query->where('tahun_ajaran_id', $tahunAjaranAktif->id) : $query;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('guru', function ($guruQuery) use ($search) {
                $guruQuery->where('name', 'like', "%{$search}%");
            })
                ->orWhereHas('kelas', function ($kelasQuery) use ($search) {
                    $kelasQuery->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('mataPelajaran', function ($mataPelajaranQuery) use ($search) {
                    $mataPelajaranQuery->where('nama', 'like', "%{$search}%");
                });
        });
    }

    // Accessors
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->mataPelajaran->nama} - {$this->kelas->nama_lengkap} ({$this->tahunAjaran->nama})";
    }
}
