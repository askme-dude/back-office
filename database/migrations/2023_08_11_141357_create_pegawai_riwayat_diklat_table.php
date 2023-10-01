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
        Schema::create('pegawai_riwayat_diklat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('jenis_diklat_id')->constrained('jenis_diklat')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('nama');
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->integer('jam_pelajaran');
            $table->text('lokasi');
            $table->string('penyelenggara');
            $table->string('no_sertifikat', 100);
            $table->date('tanggal_sertifikat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_riwayat_diklat');
    }
};
