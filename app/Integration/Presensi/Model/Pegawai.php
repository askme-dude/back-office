<?php

namespace App\Integration\Presensi\Model;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'tpegawai';
    protected $connection = 'db_presensi';
}
