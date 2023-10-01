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
        Schema::create('pegawai_riwayat_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('pendidikan_id')->nullable()->constrained('pendidikan')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('propinsi_id')->nullable()->constrained('propinsi')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('kota_id')->nullable()->constrained('kota')->nullOnDelete()->cascadeOnUpdate();
            $table->string('nama_instansi')->comment('nama tempat pendidikannya');
            $table->text('alamat');
            $table->string('no_ijazah', 100);
            $table->date('tanggal_ijazah');
            $table->string('kode_gelar_depan', 10)->nullable();
            $table->string('kode_gelar_belakang', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_riwayat_pendidikan');
    }
};
