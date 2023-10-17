<?php

namespace App\Integration\Siap\Model;

use Illuminate\Database\Eloquent\Model;

class BaseSiapModel extends Model
{
    protected $connection = 'db_siap';
    public $timestamps = false;
    public $incrementing = false;
}
