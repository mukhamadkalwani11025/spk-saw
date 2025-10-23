<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = [
        'nama',
        'bobot',
        'tipe'
    ];

    public function makanan()
    {
        return $this->belongsToMany(Makanan::class, 'makanan_kriteria')
                    ->withPivot('nilai')
                    ->withTimestamps();
    }
}
