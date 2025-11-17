<?php

namespace App\Models;

use App\Enums\JenisPengelolaan;
use App\Enums\OpsiAda;
use App\Enums\OpsiBaik;
use App\Enums\OpsiBerfungsi;
use App\Enums\OpsiTeknologi;
use App\Enums\SkalaPelayanan;
use App\Enums\StatusLahan;
use App\Enums\SumberDana;
use Illuminate\Database\Eloquent\Model;

class Spald extends Model
{
    protected $guarded = [];
    protected $appends = [
        'is_valid_map',
        // 'skala_label',
        // 'sumber_label',
        // 'status_keberfungsian_label',
        // 'kondisi_label',
        // 'status_lahan_label',
        // 'jenis_label',
        // 'teknologi_label',
        // 'status_penyedotan_label'
    ];

    public static $filterProp = [
        'nama',
        'alamat',
        'kecamatan_id',
        'kelurahan_id',
        'skala',
        'tahun_konstruksi',
        'sumber',
        'status_keberfungsian',
        'kondisi',
        'status_lahan',
        'jenis',
        'teknologi',
        'status_penyedotan',
        'status_lahan',
    ];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            // 'skala'                 => SkalaPelayanan::class,
            // 'sumber'                => SumberDana::class,
            // 'status_keberfungsian'  => OpsiBerfungsi::class,
            // 'kondisi'               => OpsiBaik::class,
            // 'status_lahan'          => StatusLahan::class,
            // 'jenis'                 => JenisPengelolaan::class,
            // 'teknologi'             => OpsiTeknologi::class,
            // 'status_penyedotan'     => OpsiAda::class,
        ];
    }

    // public function getSkalaLabelAttribute()
    // {
    //     return $this->skala?->label();
    // }

    // public function getStatusKeberfungsianLabelAttribute()
    // {
    //     return $this->status_keberfungsian?->label();
    // }

    // public function getKondisiLabelAttribute()
    // {
    //     return $this->kondisi?->label();
    // }

    // public function getStatusLahanLabelAttribute()
    // {
    //     return $this->status_lahan?->label();
    // }

    // public function getJenisLabelAttribute()
    // {
    //     return $this->jenis?->label();
    // }

    // public function getTeknologiLabelAttribute()
    // {
    //     return $this->teknologi?->label();
    // }

    // public function getSumberLabelAttribute()
    // {
    //     return $this->sumber?->label();
    // }

    // public function getStatusPenyedotanLabelAttribute()
    // {
    //     return $this->status_penyedotan?->label();
    // }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }
        if (isset($filters['alamat'])) {
            $query->where('alamat', 'like', '%' . $filters['alamat'] . '%');
        }
        if (isset($filters['kecamatan_id'])) {
            $query->where('kecamatan_id',  $filters['kecamatan_id']);
        }
        if (isset($filters['kelurahan_id'])) {
            $query->where('kelurahan_id',  $filters['kelurahan_id']);
        }
        if (isset($filters['skala'])) {
            $query->where('skala',  $filters['skala']);
        }
        if (isset($filters['tahun_konstruksi'])) {
            $query->where('tahun_konstruksi',  $filters['tahun_konstruksi']);
        }
        if (isset($filters['sumber'])) {
            $query->where('sumber',  $filters['sumber']);
        }
        if (isset($filters['status_keberfungsian'])) {
            $query->where('status_keberfungsian',  $filters['status_keberfungsian']);
        }
        if (isset($filters['kondisi'])) {
            $query->where('kondisi',  $filters['kondisi']);
        }
        if (isset($filters['status_lahan'])) {
            $query->where('status_lahan',  $filters['status_lahan']);
        }
        if (isset($filters['jenis'])) {
            $query->where('jenis',  $filters['jenis']);
        }
        if (isset($filters['teknologi'])) {
            $query->where('teknologi',  $filters['teknologi']);
        }
        if (isset($filters['status_penyedotan'])) {
            $query->where('status_penyedotan',  $filters['status_penyedotan']);
        }
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
}
