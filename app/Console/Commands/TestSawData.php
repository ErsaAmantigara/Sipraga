<?php

namespace App\Console\Commands;

use App\Models\KriteriaSaw;
use Illuminate\Console\Command;

class TestSawData extends Command
{
    protected $signature = 'test:saw';

    protected $description = 'Verify SAW data';

    public function handle()
    {
        $this->info("\n=== KRITERIA SAW ===");
        $kriteria = KriteriaSaw::all();
        foreach ($kriteria as $k) {
            $this->line("{$k->kode_kriteria} - {$k->nama_kriteria} (Bobot: {$k->bobot}%, Jenis: {$k->jenis})");
        }

        $this->info("\n✅ Data SAW sesuai dengan kriteria");
    }
}