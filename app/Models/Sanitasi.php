<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sanitasi extends Model
{
    protected $guarded = [];
    protected $appends = [
        'is_valid_map',
    ];
    public static $filterProp = [
        'tahun',
        'nama',
        'sumber',
        'kecamatan_id',
        'kelurahan_id',
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
        if (isset($filters['tahun'])) {
            $query->where('tahun',  $filters['tahun']);
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
        if (isset($filters['kelurahan_id'])) {
            $query->where('kelurahan_id',  $filters['kelurahan_id']);
        }
        if (isset($filters['sumber'])) {
            $query->where('sumber',  $filters['sumber']);
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
