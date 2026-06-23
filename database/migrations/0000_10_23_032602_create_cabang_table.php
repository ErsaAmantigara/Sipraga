<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabangTable extends Migration
{
    public function up(): void {
        Schema::create('cabang', function (Blueprint $table) {
            $table->id('cabang_id');
            $table->string('nama_cabang', 30)->unique();
            $table->text('alamat');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void {
        Schema::dropIfExists('cabang');
    }
}