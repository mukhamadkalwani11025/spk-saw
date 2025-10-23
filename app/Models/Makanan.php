<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Makanan extends Model
{
    protected $table = 'makanan'; // penting!

    protected $fillable = [
        'nama',
        'deskripsi',
        'foto'
    ];

    public function kriteria()
    {
        return $this->belongsToMany(Kriteria::class, 'makanan_kriteria')
                    ->withPivot('nilai')
                    ->withTimestamps();
    }
}
