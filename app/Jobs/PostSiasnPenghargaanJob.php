<?php

namespace App\Jobs;

use App\Data\SiasnPenghargaanData;
use App\Data\SiasnUploadedFile;
use App\Models\PegawaiRiwayatPenghargaan;
use App\Services\SiasnSimpegService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostSiasnPenghargaanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?SiasnUploadedFile $file = null;

    /**
     * Create a new job instance.
     */
    public function __construct(public PegawaiRiwayatPenghargaan $riwayatPenghargaan)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new SiasnSimpegService();

        if ($this->riwayatPenghargaan->hasMedia('media_sk')) {
            $filePath = $this->riwayatPenghargaan->getFirstMediaPath('media_sk');

            $this->file = $service->uploadFile($filePath, 892);
        }

        $data = new SiasnPenghargaanData(
            hargaId: $this->riwayatPenghargaan->penghargaan->id,
            pnsOrangId: $this->riwayatPenghargaan->pegawai->siasnPegawai->id,
            skDate: $this->riwayatPenghargaan->tanggal_sk->format('d-m-Y'),
            skNomor: $this->riwayatPenghargaan->no_sk,
            tahun: $this->riwayatPenghargaan->tahun,
            id: $this->riwayatPenghargaan->bkn_id,
            path: [$this->file],
        );

        $rwPenghargaanId = $service->postPenghargaan($data);

        if ($rwPenghargaanId) {
            $this->riwayatPenghargaan->update(['bkn_id' => $rwPenghargaanId]);
        }
    }
}
