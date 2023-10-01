<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pegawai_alamat', function (Blueprint $table) {
            $table->id();
            $table->unique(['pegawai_id', 'tipe_alamat']);
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('tipe_alamat', ["Domisili", "Asal"]);
            $table->foreignId('propinsi_id')->constrained('propinsi')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('kota_id')->constrained('kota')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('desa_id')->constrained('desa')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_pos', 5);
            $table->text('alamat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_alamat');
    }
};
