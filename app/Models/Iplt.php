<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iplt extends Model
{
    protected $guarded = [];
    protected $appends = [
        'is_valid_map',
    ];

    public static $filterProp = [
        'nama',
        'kecamatan_id',
        'kelurahan_id',
        'tahun_konstruksi',
        'kondisi_truk',
    ];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            'kecamatan_id'  => 'integer',
            'kelurahan_id'  => 'integer',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
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
        if (isset($filters['kelurahan_id'])) {
            $query->where('kelurahan_id',  $filters['kelurahan_id']);
        }
        if (isset($filters['tahun_konstruksi'])) {
            $query->where('tahun_konstruksi',  $filters['tahun_konstruksi']);
        }
        if (isset($filters['kondisi_truk'])) {
            $query->where('kondisi_truk',  $filters['kondisi_truk']);
        }
    }

    public function getIsValidMapAttribute(): bool
    {
        return valid_latlong($this->lat, $this->long);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }
}
