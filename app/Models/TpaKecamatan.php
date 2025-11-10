<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpaKecamatan extends Model
{
    protected $guarded = [];

    public function tpa()
    {
        return $this->belongsTo(Tpa::class);
    }
}
