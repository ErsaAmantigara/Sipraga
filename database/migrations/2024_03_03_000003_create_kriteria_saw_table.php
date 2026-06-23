<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kriteria_saw', function (Blueprint $table) {
            $table->id('kriteria_saw_id');
            $table->string('kode_kriteria', 20)->unique();
            $table->string('nama_kriteria', 35);
            $table->decimal('bobot', 5, 2); // Bobot dalam persen (1-100)
            $table->enum('jenis', ['benefit', 'cost']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_saw');
    }
};
