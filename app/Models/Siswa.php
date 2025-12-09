<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    /** @use HasFactory<\Database\Factories\SiswaFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'siswas';

    protected $fillable = [
        'nis',
        'nisn',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'status',
        'tanggal_lulus',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'jenis_kelamin' => 'string',
            'tanggal_lulus' => 'date',
        ];
    }

    /* -------------------------------------------------------------------------- */
    /* Relationships                                    */
    /* -------------------------------------------------------------------------- */

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswas')
            ->withPivot('tahun_ajaran_id')
            ->withTimestamps();
    }

    public function tahunAjaran()
    {
        return $this->belongsToMany(TahunAjaran::class, 'kelas_siswas')
            ->withPivot('kelas_id')
            ->withTimestamps();
    }

    public function nilaiSiswas()
    {
        return $this->hasMany(NilaiSiswa::class, 'siswa_id');
    }

    /* -------------------------------------------------------------------------- */
    /* Scopes                                    */
    /* -------------------------------------------------------------------------- */

    public function scopeSearch($query, $term)
    {
        return $query->when($term, function ($q, $search) {
            $q->where('nama', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%");
        });
    }

    public function scopeByJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    public function scopeCountSiswa($query)
    {
        return $query->count();
    }

    /* -------------------------------------------------------------------------- */
    /* Accessors & Mutators                                 */
    /* -------------------------------------------------------------------------- */

    public function getDisplayNameAttribute(): string
    {
        return "{$this->nis} - {$this->nama}";
    }

    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir->age;
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
