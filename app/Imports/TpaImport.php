<?php

namespace App\Imports;

use App\Enums\OpsiBaik;
use App\Enums\Pengelola;
use App\Enums\SumberDana;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tpa;
use Exception;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TpaImport implements ToCollection, WithValidation
{

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                // Lewati baris kosong
                if (collect($row)->filter()->isEmpty()) continue;

                // Ambil data pakai index
                $nama       = trim((string) $row[0]);
                $kecamatan  = strtolower(trim((string) $row[1]));
                $kelurahan  = strtolower(trim((string) $row[2]));
                $sumber     = trim((string) $row[3]);
                $tahun_kons = (int) $row[4];
                $tahun_opr  = (int) $row[5];
                $rencana    = $row[6] ?? null;
                $luas_sar   = $row[7] ?? null;
                $luas_sel   = $row[8] ?? null;
                $lat        = $row[9] ?? null;
                $long       = $row[10] ?? null;
                $pengelola  = trim((string) $row[11]);
                $pengelola_desc = $row[12] ?? null;
                $kondisi    = trim((string) $row[13]);

                // Validasi referensi manual
                $kec = Kecamatan::whereRaw('LOWER(nama) = ?', [$kecamatan])->first();
                if (! $kec) {
                    throw new \Exception("Kecamatan tidak valid di baris " . ($index + 2));
                }

                $kel = Kelurahan::where('kecamatan_id', $kec->id)
                    ->whereRaw('LOWER(nama) = ?', [$kelurahan])
                    ->first();

                if (! $kel) {
                    throw new \Exception("Kelurahan tidak valid di baris " . ($index + 2));
                }

                // Validasi enum (parse akan return null kalau tidak cocok)
                $sumberEnum  = SumberDana::parse($sumber);
                $pengelolaEnum = Pengelola::parse($pengelola);
                $kondisiEnum = OpsiBaik::parse($kondisi);

                if (! $sumberEnum) {
                    throw new \Exception("Sumber tidak valid di baris " . ($index + 2));
                }
                if (! $pengelolaEnum) {
                    throw new \Exception("Pengelola tidak valid di baris " . ($index + 2));
                }
                if (! $kondisiEnum) {
                    throw new \Exception("Kondisi tidak valid di baris " . ($index + 2));
                }

                // Simpan data
                Tpa::create([
                    'nama'             => $nama,
                    'kecamatan_id'     => $kec->id,
                    'kelurahan_id'     => $kel->id,
                    'lat'              => $lat ?: null,
                    'long'             => $long ?: null,
                    'sumber'           => $sumberEnum,
                    'tahun_konstruksi' => $tahun_kons,
                    'tahun_beroperasi' => $tahun_opr,
                    'rencana'          => $rencana,
                    'luas_sarana'      => $luas_sar,
                    'luas_sel'         => $luas_sel,
                    'pengelola'        => $pengelolaEnum,
                    'pengelola_desc'   => $pengelola_desc,
                    'kondisi'          => $kondisiEnum,
                ]);
            }
        });
    }

    public function rules(): array
    {
        return [
            '*.0'  => ['required', 'string', 'max:200'], // nama
            '*.1'  => ['required', 'string'], // kecamatan
            '*.2'  => ['required', 'string'], // kelurahan
            '*.3'  => ['required', Rule::in(array_column(SumberDana::cases(), 'value'))], // sumber
            '*.4'  => ['date_format:Y-m-d'], // tahun konstruksi
            '*.5'  => ['required', 'integer', 'digits:4'], // tahun beroperasi
            '*.6'  => ['nullable', 'string'], // rencana
            '*.7'  => ['nullable', 'numeric', 'gte:0'], // luas sarana
            '*.8'  => ['nullable', 'numeric', 'gte:0'], // luas sel
            '*.9'  => ['nullable', 'numeric', 'between:-90,90'], // lat
            '*.10' => ['nullable', 'numeric', 'between:-180,180'], // long
            '*.11' => ['required', Rule::in(array_column(Pengelola::cases(), 'value'))], // pengelola
            '*.12' => ['nullable', 'string'], // pengelola desc
            '*.13' => ['required', Rule::in(array_column(OpsiBaik::cases(), 'value'))], // kondisi
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.0.required' => 'Kolom Nama wajib diisi.',
            '*.1.required' => 'Kolom Kecamatan wajib diisi.',
            '*.2.required' => 'Kolom Kelurahan wajib diisi.',
            '*.3.in'       => 'Nilai Sumber tidak valid.',
            '*.11.in'      => 'Nilai Pengelola tidak valid.',
            '*.13.in'      => 'Nilai Kondisi tidak valid.',
        ];
    }
}
