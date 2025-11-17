<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpstKecamatan extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
        ];
    }

    public function tpst()
    {
        return $this->belongsTo(Tpst::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
