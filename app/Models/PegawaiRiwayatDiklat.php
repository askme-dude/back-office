<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PegawaiRiwayatDiklat extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'pegawai_riwayat_diklat';
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function jenisDiklat()
    {
        return $this->belongsTo(JenisDiklat::class);
    }
}
