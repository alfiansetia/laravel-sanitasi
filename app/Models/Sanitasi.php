<?php

namespace App\Models;

use App\Enums\SumberDana;
use Illuminate\Database\Eloquent\Model;

class Sanitasi extends Model
{
    protected $guarded = [];
    protected $appends = [
        'is_valid_map',
        // 'sumber_label'
    ];
    public static $filterProp = [
        'tahun',
        'nama',
        'lokasi',
        'sumber'
    ];

    protected function casts(): array
    {
        return [
            // 'sumber'  => SumberDana::class,
        ];
    }

    // public function getSumberLabelAttribute()
    // {
    //     return $this->sumber?->label();
    // }

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
}
