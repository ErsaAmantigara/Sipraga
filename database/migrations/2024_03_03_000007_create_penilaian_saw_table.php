<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_saw', function (Blueprint $table) {
            $table->id('penilaian_saw_id');
            $table->foreignId('pengaduan_id')->unique()->constrained('pengaduan', 'pengaduan_id')->cascadeOnDelete();
            $table->decimal('c1_tingkat_urgensi', 5, 2)->nullable();
            $table->decimal('c2_lama_waktu_pelaporan', 5, 2)->nullable();
            $table->decimal('c3_jenis_pelanggan', 5, 2)->nullable();
            $table->decimal('c4_jarak_kelokasi', 5, 2)->nullable();
            $table->decimal('normalisasi_c1', 5, 4)->nullable();
            $table->decimal('normalisasi_c2', 5, 4)->nullable();
            $table->decimal('normalisasi_c3', 5, 4)->nullable();
            $table->decimal('normalisasi_c4', 5, 4)->nullable();
            $table->decimal('nilai_preferensi', 5, 4)->nullable();
            $table->enum('kategori_prioritas', ['Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi'])->nullable();
            $table->integer('ranking')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_saw');
    }
};
