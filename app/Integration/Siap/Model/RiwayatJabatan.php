<?php

namespace App\Integration\Siap\Model;

class RiwayatJabatan extends BaseSiapModel
{
    protected $table = 'RiwayatJabatan';
    protected $primaryKey = 'NoSK';
    protected $casts = [
        'TMTJabatan' => 'date:d-m-Y'
    ];
}
