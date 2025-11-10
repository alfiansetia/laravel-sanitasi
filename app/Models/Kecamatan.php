<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $guarded = [];

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
}
