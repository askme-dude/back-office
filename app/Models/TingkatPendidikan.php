<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatPendidikan extends Model
{
    use HasFactory;

    protected $table = 'tingkat_pendidikan';
    protected $guarded = [];

    public function pendidikan()
    {
        return $this->hasMany(Pendidikan::class);
    }
}
