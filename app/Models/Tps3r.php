<?php

namespace App\Models;

use App\Enums\OpsiBerfungsi;
use Illuminate\Database\Eloquent\Model;

class Tps3r extends Model
{
    protected $guarded = [];
    protected $appends = [
        // 'status_label'
    ];
    public static $filterProp = [
        'tahun_konstruksi',
        'tahun_beroperasi',
        'status',
    ];

    protected function casts(): array
    {
        return [
            // 'status'  => OpsiBerfungsi::class,
        ];
    }

    // public function getStatusLabelAttribute()
    // {
    //     return $this->status?->label();
    // }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['tahun_konstruksi'])) {
            $query->where('tahun_konstruksi',  $filters['tahun_konstruksi']);
        }
        if (isset($filters['tahun_beroperasi'])) {
            $query->where('tahun_beroperasi',  $filters['tahun_beroperasi']);
        }
        if (isset($filters['status'])) {
            $query->where('status',  $filters['status']);
        }
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
