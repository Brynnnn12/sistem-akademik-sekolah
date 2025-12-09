<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataPelajaran extends Model
{
    /** @use HasFactory<\Database\Factories\MataPelajaranFactory> */
    use HasFactory, SoftDeletes;


    // 'kode' dihapus dari fillable karena digenerate otomatis
    protected $fillable = [
        'nama',
        'kkm',
    ];

    protected function casts(): array
    {
        return [
            'kkm' => 'integer',
        ];
    }

    /* -------------------------------------------------------------------------- */
    /* Scopes                                    */
    /* -------------------------------------------------------------------------- */

    public function scopeSearch($query, $term)
    {
        return $query->when($term, function ($q, $search) {
            $q->where('nama', 'like', "%{$search}%")
                ->orWhere('kode', 'like', "%{$search}%");
        });
    }

    /* -------------------------------------------------------------------------- */
    /* Model Events                                 */
    /* -------------------------------------------------------------------------- */

    protected static function booted()
    {
        static::creating(function ($mataPelajaran) {
            // Generate Kode: 3 Huruf Depan Nama (Upper) + 3 Angka Acak
            // Contoh: MATEMATIKA -> MAT-832
            $prefix = strtoupper(substr($mataPelajaran->nama, 0, 3));

            do {
                $code = $prefix . '-' . rand(100, 999);
            } while (self::where('kode', $code)->exists()); // Cek unik

            $mataPelajaran->kode = $code;
        });
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->kode} - {$this->nama}";
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('kode', 'asc');
    }

    public function scopeKkmAbove($query, $value)
    {
        return $query->where('kkm', '>=', $value);
    }

    /* -------------------------------------------------------------------------- */
    /* Relationships                              */
    /* -------------------------------------------------------------------------- */

    public function penugasanMengajar()
    {
        return $this->hasMany(PenugasanMengajar::class);
    }
}
