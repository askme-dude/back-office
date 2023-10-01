<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisUnitKerja extends Model
{
    use HasFactory;

    protected $table = 'jenis_unit_kerja';
    protected $guarded = [];

    public function unit_kerja()
    {
        return $this->hasMany(UnitKerja::class);
    }
}
