<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpaKecamatan extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            'kecamatan_id'  => 'integer',
            'tpa_id'        => 'integer',
        ];
    }

    public function tpa()
    {
        return $this->belongsTo(Tpa::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
