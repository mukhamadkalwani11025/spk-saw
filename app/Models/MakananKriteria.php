<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MakananKriteria extends Model
{
    protected $table = 'makanan_kriteria'; // nama tabel pivot

    protected $fillable = [
        'makanans_id',
        'kriterias_id',
        'nilai',
    ];

    public function makanan()
    {
        return $this->belongsTo(Makanan::class, 'makanans_id', 'id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriterias_id', 'id');
    }
}
