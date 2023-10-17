<?php

namespace App\Integration\Siap\Model;

class RiwayatPangkat extends BaseSiapModel
{
    protected $table = 'RiwayatPangkat';
    protected $primaryKey = 'NoSK';
    protected $casts = [
        'TglNota' => 'date:d-m-Y',
        'TglSK' => 'date:d-m-Y',
        'TMTPangkat' => 'date:d-m-Y',
    ];

    public function pegawai(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'PegawaiID', 'PegawaiID');
    }

    public function pangkat(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pangkat::class, 'PangkatID', 'PangkatID');
    }
}
