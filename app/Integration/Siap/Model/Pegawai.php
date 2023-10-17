<?php

namespace App\Integration\Siap\Model;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Pegawai extends BaseSiapModel
{
    use HasRelationships;

    protected $table = 'Pegawai';
    protected $primaryKey = 'PegawaiID';
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'TglLahir' => 'date:d-m-Y',
        'TglPensiun' => 'date:d-m-Y'
    ];

    public function agama(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Agama::class, 'AgamaID', 'AgamaID');
    }

    public function riwayatPangkat(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RiwayatPangkat::class, 'PegawaiID', 'PegawaiID');
    }

    public function riwayatPangkatTerakhir(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->riwayatPangkat()->one()->ofMany('NoUrut');
    }

    public function pangkatTerakhir(): \Staudenmeir\EloquentHasManyDeep\HasOneDeep
    {
        return $this->hasOneDeep(Pangkat::class, [RiwayatPangkat::class], ['PegawaiID', 'PangkatID'], ['PegawaiID', 'PangkatID'])
            ->latest('RiwayatPangkat.NoUrut');
    }

    public function riwayatJabatan(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RiwayatJabatan::class, 'PegawaiID', 'PegawaiID');
    }

    public function riwayatJabatanTerakhir(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->riwayatJabatan()->one()->ofMany('TMTJabatan');
    }

    public function statusPegawai(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StatusPegawai::class, 'StatusPegawaiID', 'StatusPegawaiID');
    }

    public function kedudukan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kedudukan::class, 'KedudukanID', 'KedudukanID');
    }

    public function jenisPegawai(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(JenisPegawai::class, 'JenisPegawaiID', 'JenisPegawaiID');
    }

    public function satker(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Satker::class, 'SatkerID', 'SatkerID');
    }

    public function tipePegawai(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TipePegawai::class, 'TipePegawai', 'TipePegawaiId');
    }
}
