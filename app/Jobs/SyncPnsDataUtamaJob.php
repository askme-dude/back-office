<?php

namespace App\Jobs;

use App\Models\Pegawai;
use App\Models\Siasn\SiasnPnsDataUtama;
use App\Services\PresensiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPnsDataUtamaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public bool $deleteWhenMissingModels = true;

    public function __construct(public SiasnPnsDataUtama $siasnDataPegawai)
    {
    }

    public function handle(): void
    {
        $name = explode(" ", $this->siasnDataPegawai->nama);
        $lastName = count($name) === 1 ? null : array_pop($name);
        $firstName = implode(" ", $name);

        Pegawai::updateOrCreate(['nip' => $this->siasnDataPegawai->nipBaru], [
            'nik' => $this->siasnDataPegawai->nik,
            'nama_depan' => $firstName,
            'nama_belakang' => $lastName,
            'jenis_kelamin' => $this->siasnDataPegawai->jenisKelamin === 'M' ? 'L' : 'P',
            'agama_id' => $this->siasnDataPegawai->agamaId,
            'jenis_kawin_id' => $this->siasnDataPegawai->jenisKawinId,
            'tempat_lahir' => $this->siasnDataPegawai->tempatLahir,
            'tanggal_lahir' => date_create_from_format('d-m-Y', $this->siasnDataPegawai->tglLahir)->format('Y-m-d'),
            'no_telp' => $this->siasnDataPegawai->noHp,
            'jenis_pegawai_id' => $this->siasnDataPegawai->jenisPegawaiId,
            'status_pegawai_id' => 1,
            'status_dinas' => $this->siasnDataPegawai->kedudukanPnsNama === 'Aktif',
            'tanggal_berhenti' => !$this->siasnDataPegawai->tmtPensiun ? null : date_create_from_format('d-m-Y', $this->siasnDataPegawai->tmtPensiun)->format('Y-m-d'),
            'tanggal_wafat' => !$this->siasnDataPegawai->tglMeninggal ? null : date_create_from_format('d-m-Y', $this->siasnDataPegawai->tglMeninggal)->format('Y-m-d'),
            'no_kartu_siasn' => $this->siasnDataPegawai->kartuAsn,
            'no_bpjs' => $this->siasnDataPegawai->bpjs,
            'no_taspen' => $this->siasnDataPegawai->noTaspen,
            'npwp' => $this->siasnDataPegawai->noNpwp,
            'no_enroll' => PresensiService::getEnrollNumberByNip($this->siasnDataPegawai->nipBaru),
        ]);
    }
}
