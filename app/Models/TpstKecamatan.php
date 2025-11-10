<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpstKecamatan extends Model
{
    protected $guarded = [];

    public function tpst()
    {
        return $this->belongsTo(Tpst::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
