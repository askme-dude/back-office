<?php

namespace App\Models\Siasn;

use App\Models\Penghargaan;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiasnPnsRwPenghargaan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'siasn_pns_rw_penghargaan';

    protected $guarded = [];

    protected $casts = [
        'path' => 'array'
    ];

    public function siasnPegawai(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SiasnPnsDataUtama::class, 'pnsOrangId', 'id');
    }

    public function penghargaan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Penghargaan::class, 'hargaId', 'id');
    }
}
