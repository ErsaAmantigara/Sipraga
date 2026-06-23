<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            CabangSeeder::class,
            KriteriaSawSeeder::class,
            UserSeeder::class,
            DemoFlowSeeder::class,
            PengaduanSelesaiSeeder::class,
        ]);
    }
}
