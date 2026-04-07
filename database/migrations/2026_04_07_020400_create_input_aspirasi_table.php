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
        Schema::create('input_aspirasi', function (Blueprint $table) {
            $table->id('id_pelaporan');
            $table->foreignId('nis')
                ->constrained('siswa', 'nis')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('id_kategori')
                ->constrained('kategori', 'id_kategori')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('id_aspirasi')
                ->nullable()
                ->constrained('aspirasi', 'id_aspirasi')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('lokasi', 50);
            $table->string('ket', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_aspirasi');
    }
};
