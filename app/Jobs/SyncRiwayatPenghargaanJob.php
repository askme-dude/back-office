<?php

namespace App\Jobs;

use App\Models\PegawaiRiwayatPenghargaan;
use App\Models\Siasn\SiasnPnsRwPenghargaan;
use App\Services\SiasnSimpegService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class SyncRiwayatPenghargaanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public SiasnPnsRwPenghargaan $siasnData)
    {
    }

    public function handle(): void
    {
        $riwayat = PegawaiRiwayatPenghargaan::updateOrCreate(['bkn_id' => $this->siasnData->id], [
            'pegawai_id' => $this->siasnData->siasnPegawai->pegawai->id,
            'penghargaan_id' => $this->siasnData->hargaId,
            'no_sk' => $this->siasnData->skNomor,
            'tanggal_sk' => !$this->siasnData->skDate ? null : date_create_from_format('d-m-Y', $this->siasnData->skDate)->format('Y-m-d'),
            'tahun' => $this->siasnData->tahun,
        ]);

        if (empty($this->siasnData->path)) {
            return;
        }

        $path = Arr::has($this->siasnData->path, '892') ? $this->siasnData->path[892] : $this->siasnData->path[0];

        if ($uri = Arr::get($path, 'dok_uri')) {
            $content = (new SiasnSimpegService())->downloadFile($uri);

            $name = Arr::get($path, 'dok_nama') ?? 'dok_siasn';

            $fileName = Arr::last(explode('/', $uri));

            $riwayat->clearMediaCollection('media_sk');

            $riwayat->addMediaFromStream($content)
                ->usingName($name)
                ->usingFileName($fileName)
                ->toMediaCollection('media_sk');
        }
    }
}
