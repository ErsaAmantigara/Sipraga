<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cabang::updateOrCreate(
            ['nama_cabang' => 'Ulu'],
            ['alamat' => 'Jl. Jakabaring Kompleks Jaka Permai', 'latitude' => -3.00722972, 'longitude' => 104.77453411, 'is_active' => 1]
        );
        Cabang::updateOrCreate(
            ['nama_cabang' => 'Ilir'],
            ['alamat' => 'Jl. Sapta Marga Sematang Borang', 'latitude' => -2.94040717, 'longitude' => 104.78288146, 'is_active' => 1]
        );
        Cabang::updateOrCreate(
            ['nama_cabang' => 'Demang'],
            ['alamat' => 'Jl. Demang Lebar Daun', 'latitude' => -2.97933620, 'longitude' => 104.72398763, 'is_active' => 1]
        );
    }
}
