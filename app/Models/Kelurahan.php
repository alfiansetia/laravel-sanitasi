<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $guarded = [];
    public static $filterProp = [
        'kode',
        'nama',
        'kecamatan_id',
    ];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            'kecamatan_id'  => 'integer',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['kode'])) {
            $query->where('kode', $filters['kode']);
        }
        if (isset($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }
        if (!empty($filters['kecamatan_id'])) {
            if (is_array($filters['kecamatan_id'])) {
                $query->whereIn('kecamatan_id', $filters['kecamatan_id']);
            } else {
                $query->where('kecamatan_id', $filters['kecamatan_id']);
            }
        }
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function sanitasis()
    {
        return $this->hasMany(Sanitasi::class);
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
