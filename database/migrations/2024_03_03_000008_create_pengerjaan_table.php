<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengerjaan', function (Blueprint $table) {
            $table->id('pengerjaan_id');
            $table->foreignId('pengaduan_id')->constrained('pengaduan', 'pengaduan_id')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->onDelete('set null');

            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();

            $table->enum('status_pengerjaan', [
                'dalam_pengecekan',
                'dalam_pengerjaan',
                'selesai'
            ])->nullable();

            $table->string('foto_sebelum', 50)->nullable();
            $table->string('foto_proses', 50)->nullable();
            $table->string('foto_sesudah', 50)->nullable();

            $table->text('material')->nullable();
            $table->text('keterangan_teknisi')->nullable();

            $table->integer('rating_nilai')->nullable()->comment('1-5');
            $table->text('rating_komentar')->nullable();
            $table->timestamp('tanggal_rating')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengerjaan');
    }
};
