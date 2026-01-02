<?php

namespace App\Imports;

use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JadwalImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Jadwal([
            'hari' => $row['hari'],
            'jam' => $row['jam'],
            'guru' => $row['guru'],
            'semester' => $row['semester'],
            'mata_pelajaran' => $row['mata_pelajaran'],
            'kelas' => $row['kelas'],
            'sks' => $row['sks'],
            'tahun_akademik' => $row['tahun_akademik'],
        ]);
    }
}
