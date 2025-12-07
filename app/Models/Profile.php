<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'no_hp',
        'alamat',
        'jenis_kelamin',
        'photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return url(Storage::url($value));
    }
}
