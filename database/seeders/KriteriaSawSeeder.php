<?php

namespace Database\Seeders;

use App\Models\KriteriaSaw;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaSawSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $kriteria = [
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Tingkat Urgensi',
                'bobot' => 50.00,
                'jenis' => 'benefit',
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Lama Waktu Pelaporan',
                'bobot' => 25.00,
                'jenis' => 'benefit',
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Jenis Pelanggan',
                'bobot' => 15.00,
                'jenis' => 'benefit',
            ],
            [
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'Jarak ke Lokasi',
                'bobot' => 10.00,
                'jenis' => 'cost',
            ],
        ];

        foreach ($kriteria as $data) {
            KriteriaSaw::updateOrCreate(
                ['kode_kriteria' => $data['kode_kriteria']],
                $data
            );
        }
    }
}
