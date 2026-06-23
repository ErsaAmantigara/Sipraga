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
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id('pengaduan_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('nomor_pengaduan', 20)->unique();
            $table->timestamp('tanggal_pengaduan')->useCurrent();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->enum('jenis_keluhan', [
                'Pipa Bocor',
                'Pipa Service',
                'Rekening Tagihan',
                'Meter Gas Rusak',
                'Kompor Tidak Hidup',
                'Api Kompor Kecil',
                'Perubahan Instalasi Pipa',
                'Perubahan Instalasi Meteran',
                'Penambahan Titik Api',
                'Instalasi Kompor Baru',
                'Cabut Meteran (Stop Berlangganan)',
            ]);
            $table->text('deskripsi_keluhan');
            $table->integer('stand_meter_terakhir');
            $table->string('foto_keluhan', 50);

            $table->enum('status_pengaduan', [
                'pengajuan',
                'valid',
                'tidak_valid',
                'teknisi_ditugaskan',
                'selesai'
            ])->default('pengajuan');

            $table->text('keterangan_cs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
