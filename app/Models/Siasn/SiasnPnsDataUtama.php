<?php

namespace App\Models\Siasn;

use App\Integration\Siap\Model\Pegawai as SiapPegawai;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiasnPnsDataUtama extends Model
{
    use HasFactory;

    protected $table = 'siasn_pns_data_utama';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function siap(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SiapPegawai::class, 'nipBaru', 'NIPBaru');
    }

    public function pegawai(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'nipBaru', 'nip');
    }
}
