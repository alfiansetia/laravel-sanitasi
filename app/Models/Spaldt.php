<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spaldt extends Model
{
    protected $guarded = [];
    protected $appends = ['is_valid_map'];

    public function getIsValidMapAttribute(): bool
    {
        return valid_latlong($this->lat, $this->long);
    }
}
