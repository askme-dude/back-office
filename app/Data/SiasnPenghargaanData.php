<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Exceptions\DataMissingFeature;

class SiasnPenghargaanData extends Data
{
    /** @var array<SiasnUploadedFile> */
    public mixed $path;

    /**
     * @throws DataMissingFeature
     */
    public function __construct(
        public string  $hargaId,
        public string  $pnsOrangId,
        #[DateFormat('dd-mm-yyyy')]
        public string  $skDate,
        public string  $skNomor,
        public int     $tahun,
        public ?string $id = null,
                       $path = null,

    )
    {
        if ($path) {
            foreach ($path as $single) {
                if (empty($single)) {
                    continue;
                }

                if (!$single instanceof SiasnUploadedFile) {
                    throw new DataMissingFeature('Path harus berupa instance dari SiasnUploadedFile');
                }
            }
        }

        $this->path = $path;
    }
}
