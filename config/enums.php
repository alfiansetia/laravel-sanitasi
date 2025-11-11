<?php

use App\Enums\JenisPengelolaan;
use App\Enums\Kondisi;
use App\Enums\OpsiAda;
use App\Enums\OpsiBaik;
use App\Enums\OpsiBerfungsi;
use App\Enums\OpsiTeknologi;
use App\Enums\SkalaPelayanan;
use App\Enums\StatusLahan;
use App\Enums\SumberDana;

return [
    'sumber_dana'       => SumberDana::cases(),
    'skala_pelayanan'   => SkalaPelayanan::cases(),
    'opsi_baik'         => OpsiBaik::cases(),
    'opsi_ada'          => OpsiAda::cases(),
    'opsi_befungsi'     => OpsiBerfungsi::cases(),
    'status_lahan'      => StatusLahan::cases(),
    'jenis_pengelolaan' => JenisPengelolaan::cases(),
    'opsi_teknologi'    => OpsiTeknologi::cases(),
];
