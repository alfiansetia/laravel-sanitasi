<?php

namespace App\Exports;

use App\Models\Tpa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TpaExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, WithStrictNullComparison
{
    protected  $filters = [];

    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function query()
    {
        return Tpa::query()->filter($this->filters)->with(['kecamatan', 'kelurahan', 'kecamatan_terlayani.kecamatan']);
    }

    public function headings(): array
    {
        return [
            "NO",
            "Nama TPA",
            "Kecamatan",
            "Kelurahan/Desa",
            "Sumber Anggaran",
            "Tahun Konstruksi",
            "Tahun Beroperasi",
            "Rencana Umur Beroperasi (TH)",
            "Kecamatan Terlayani",
            "Luas Sarana (ha)",
            "Luas Sel (ha)",
            "Jenis Pengelola",
            "Kondisi TPA",
            "Koordinat",
        ];
    }

    public function map($row): array
    {
        static $number = 1;
        $kecamatan_terlayani = $row->kecamatan_terlayani->isEmpty()
            ? '' : implode(', ', $row->kecamatan_terlayani->pluck('kecamatan.nama')->toArray());
        return [
            $number++,
            $row->nama,
            $row->kecamatan->nama ?? '',
            $row->kelurahan->nama ?? '',
            $row->sumber,
            $row->tahun_konstruksi,
            $row->tahun_beroperasi,
            $row->rencana,
            $kecamatan_terlayani,
            $row->luas_sarana,
            $row->luas_sel,
            $row->pengelola . ($row->pengelola_desc ? (' ' . $row->pengelola_desc) : ''),
            $row->kondisi,
            $row->lat . ' ' . $row->long,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER_0,
            'K' => NumberFormat::FORMAT_NUMBER_0,
        ];
    }
}
