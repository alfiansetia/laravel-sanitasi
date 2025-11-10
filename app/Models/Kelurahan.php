<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $guarded = [];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['kode'])) {
            $query->where('kode', $filters['kode']);
        }
        if (isset($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }
        if (isset($filters['kecamatan_id'])) {
            $query->where('kecamatan_id', $filters['kecamatan_id']);
        }
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
