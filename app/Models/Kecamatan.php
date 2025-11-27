<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $guarded = [];
    public static $filterProp = [
        'kode',
        'nama',
    ];

    protected function casts(): array
    {
        return [
            'id'                => 'integer',
            'tpas_count'        => 'integer',
            'tpsts_count'       => 'integer',
            'tps3rs_count'      => 'integer',
            'iplts_count'       => 'integer',
            'spalds_count'      => 'integer',
            'sanitasis_count'   => 'integer',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['kode'])) {
            $query->where('kode',  $filters['kode']);
        }
        if (isset($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }
    }

    public function sanitasis()
    {
        return $this->hasMany(Sanitasi::class);
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
