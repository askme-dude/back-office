<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pegawai extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'pegawai';
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }

    public function jenis_kawin()
    {
        return $this->belongsTo(JenisKawin::class);
    }

    public function jenis_pegawai()
    {
        return $this->belongsTo(JenisPegawai::class);
    }

    public function status_pegawai()
    {
        return $this->belongsTo(StatusPegawai::class);
    }

    public function pegawai_riwayat_jabatan()
    {
        return $this->hasMany(PegawaiRiwayatJabatan::class);
    }

    public function pegawai_alamat()
    {
        return $this->hasMany(PegawaiAlamat::class);
    }

    public function pegawai_rekening()
    {
        return $this->hasMany(PegawaiRekening::class);
    }

    public function pegawai_riwayat_diklat()
    {
        return $this->hasMany(PegawaiRiwayatDiklat::class);
    }

    public function pegawai_tmt_gaji()
    {
        return $this->hasMany(PegawaiTmtGaji::class);
    }

    public function pegawai_riwayat_pendidikan()
    {
        return $this->hasMany(PegawaiRiwayatPendidikan::class);
    }

    public function pegawai_riwayat_umak()
    {
        return $this->hasMany(PegawaiRiwayatUmak::class);
    }

    public function pegawai_tambahan_mk()
    {
        return $this->hasMany(PegawaiTambahanMk::class);
    }

    public function pegawai_riwayat_thp()
    {
        return $this->hasMany(PegawaiRiwayatThp::class);
    }

    public function pegawai_anak()
    {
        return $this->hasMany(PegawaiAnak::class);
    }

    public function pegawai_suami_istri()
    {
        return $this->hasMany(PegawaiSuamiIstri::class);
    }
}
