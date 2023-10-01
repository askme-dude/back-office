<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\VillageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use VillageTrait, HasFactory;

    protected $table = 'desa';
    public $timestamps = false;

    protected $hidden = [
        'kecamatan_id'
    ];

    /**
     * Desa belongs to Kecamatan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function pegawai_alamat()
    {
        return $this->hasMany(PegawaiAlamat::class);
    }
}
