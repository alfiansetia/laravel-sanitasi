<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tpst extends Model
{
    protected $guarded = [];
    protected $appends = ['is_valid_map', 'kecamatan_terlayani_ids'];
    public static $filterProp = [
        'nama',
        'kecamatan_id',
        'kelurahan_id',
        'sumber',
        'tahun_konstruksi',
        'tahun_beroperasi',
        'pengelola',
        'pengelola_desc',
        'kondisi',
    ];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }
        if (isset($filters['kecamatan_id'])) {
            $query->where('kecamatan_id',  $filters['kecamatan_id']);
        }
        if (isset($filters['kelurahan_id'])) {
            $query->where('kelurahan_id',  $filters['kelurahan_id']);
        }
        if (isset($filters['sumber'])) {
            $query->where('sumber',  $filters['sumber']);
        }
        if (isset($filters['tahun_konstruksi'])) {
            $query->where('tahun_konstruksi',  $filters['tahun_konstruksi']);
        }
        if (isset($filters['tahun_beroperasi'])) {
            $query->where('tahun_beroperasi',  $filters['tahun_beroperasi']);
        }
        if (isset($filters['pengelola'])) {
            $query->where('pengelola',  $filters['pengelola']);
        }
        if (isset($filters['pengelola_desc'])) {
            $query->where('pengelola_desc', 'like', '%' . $filters['pengelola_desc'] . '%');
        }
        if (isset($filters['kondisi'])) {
            $query->where('kondisi',  $filters['kondisi']);
        }
    }

    public function getKecamatanTerlayaniIdsAttribute()
    {
        return $this->kecamatan_terlayani()
            ->pluck('kecamatan_id')
            ->toArray();
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
        return $this->hasMany(TpstKecamatan::class);
    }
}
