<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class UnitKerja extends Model
{
    use HasFactory, HasRecursiveRelationships;

    protected $table = 'unit_kerja';
    protected $guarded = [];

    public function jenisUnitKerja()
    {
        return $this->belongsTo(JenisUnitKerja::class);
    }
}
