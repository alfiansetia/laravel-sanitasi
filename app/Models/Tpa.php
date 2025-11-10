<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tpa extends Model
{
    protected $guarded = [];
    protected $appends = ['is_valid_map'];
    protected $casts = [
        'kecamatan_terlayani' => 'array',
    ];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['tahun'])) {
            $query->where('tahun',  $filters['tahun']);
        }
        if (isset($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }
        if (isset($filters['lokasi'])) {
            $query->where('lokasi', 'like', '%' . $filters['lokasi'] . '%');
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

    public function kecamatan_terlayani()
    {
        return $this->hasMany(Kelurahan::class);
    }
}
