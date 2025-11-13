<?php

use App\Enums\JenisPengelolaan;
use App\Enums\OpsiAda;
use App\Enums\OpsiBaik;
use App\Enums\OpsiBerfungsi;
use App\Enums\OpsiTeknologi;
use App\Enums\Pengelola;
use App\Enums\SkalaPelayanan;
use App\Enums\StatusLahan;
use App\Enums\SumberDana;

// return [
//     'sumber_dana'       => SumberDana::cases(),
//     'skala_pelayanan'   => SkalaPelayanan::cases(),
//     'opsi_baik'         => OpsiBaik::cases(),
//     'opsi_ada'          => OpsiAda::cases(),
//     'opsi_befungsi'     => OpsiBerfungsi::cases(),
//     'status_lahan'      => StatusLahan::cases(),
//     'jenis_pengelolaan' => JenisPengelolaan::cases(),
//     'opsi_teknologi'    => OpsiTeknologi::cases(),
//     'pengelola'         => Pengelola::cases(),
// ];

return [
    'sumber_dana'       => ["DAK", "DAU"],
    'skala_pelayanan'   => ['Perkotaan', 'Kawasan Tertentu', 'Permukiman'],
    'opsi_baik'         => ['Baik', 'Tidak Baik'],
    'opsi_ada'          => ['Ada', 'Tidak Ada'],
    'opsi_befungsi'     => ['Berfungsi', 'Tidak Berfungsi'],
    'status_lahan'      => ['Hibah', 'Aset Pemda', 'Swasta', 'Aset Masyarakat (Pemilik Rumah)',],
    'jenis_pengelolaan' => ['Institusi', 'Masyarakat'],
    'opsi_teknologi'    => ['Tangki Septik Individual', 'Tangki Septik Komunal'],
    'pengelola'         => ['DINAS', 'UPT',],
];
