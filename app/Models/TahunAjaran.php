<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahunAjaran extends Model
{
    /** @use HasFactory<\Database\Factories\TahunAjaranFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'semester',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function getAktifLabelAttribute(): string
    {
        return $this->aktif ? 'Aktif' : 'Tidak Aktif';
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeGanjil($query)
    {
        return $query->where('semester', 'ganjil');
    }
}
