<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SiasnUploadedFile extends Data
{
    public function __construct(
        public string $dok_id,
        public string $dok_nama,
        public string $dok_uri,
        public string $slug,
        public string $object,
    )
    {
    }
}
