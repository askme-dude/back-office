<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\DistrictTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use DistrictTrait, HasFactory;

    protected $table = 'kecamatan';
    public $timestamps = false;
    protected $hidden = [
        'kota_id'
    ];

    /**
     * Kecamatan belongs to Kota.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    /**
     * Kecamatan has many villages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function desa()
    {
        return $this->hasMany(Desa::class);
    }

    public function pegawai_alamat()
    {
        return $this->hasMany(PegawaiAlamat::class);
    }
}
