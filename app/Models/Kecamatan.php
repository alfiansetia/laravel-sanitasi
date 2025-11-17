<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $guarded = [];
    public static $filterProp = [
        'nama',
    ];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }
    }

    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class);
    }

    public function tpas()
    {
        return $this->hasMany(Tpa::class);
    }

    public function tpsts()
    {
        return $this->hasMany(Tpst::class);
    }

    public function tps3rs()
    {
        return $this->hasMany(Tps3r::class);
    }

    public function iplts()
    {
        return $this->hasMany(Iplt::class);
    }

    public function spalds()
    {
        return $this->hasMany(Spald::class);
    }
}
