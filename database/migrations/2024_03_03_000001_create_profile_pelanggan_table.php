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
        Schema::create('profile_pelanggan', function (Blueprint $table) {
            $table->id('profile_pelanggan_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('no_id_pelanggan', 20)->unique();
            $table->enum('jenis_pelanggan', ['R1', 'R2', 'PK1', 'PK2']);
            $table->text('alamat');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_pelanggan');
    }
};
