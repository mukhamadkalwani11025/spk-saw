<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi';

    protected $fillable = [
        'user_id',
        'makanans_id',
        'skor',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Makanan
    public function makanan()
    {
        return $this->belongsTo(Makanan::class, 'makanans_id');
    }
}
