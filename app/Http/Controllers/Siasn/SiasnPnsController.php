<?php

namespace App\Http\Controllers\Siasn;

use App\Http\Controllers\Controller;
use App\Integration\Siap\Model\Satker;
use App\Models\Siasn\SiasnPnsDataUtama;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class SiasnPnsController extends Controller
{
    public function index()
    {
        return Inertia::render('Siasn/Asn/Index', [
            'asn' => fn() => QueryBuilder::for(SiasnPnsDataUtama::class)
                ->select('id', 'nama', 'nipBaru', 'golRuangAkhir', 'kedudukanPnsNama', 'jabatanNama', 'unorNama', 'updated_at')
                ->allowedFilters(['nama', 'nipBaru', 'golRuangAkhir', 'kedudukanPnsNama', 'jabatanNama', 'unorNama', 'updated_at'])
                ->allowedSorts(['nama', 'nipBaru', 'golRuangAkhir', 'kedudukanPnsNama', 'jabatanNama', 'unorNama', 'updated_at'])
                ->paginate(request('per_page', 15))
                ->onEachSide(1)
                ->appends(request()->query()),
        ]);
    }

    public function show(SiasnPnsDataUtama $asn)
    {
        return Inertia::render('Siasn/Asn/Show', [
            'siasn' => $asn,
            'siap' => $asn->siap?->load(['agama', 'pangkatTerakhir', 'riwayatPangkatTerakhir', 'riwayatJabatanTerakhir', 'statusPegawai', 'kedudukan', 'jenisPegawai', 'tipePegawai']),
            'unorInduk' => Satker::find(substr($asn->siap?->SatkerID, 0, 4)),
            'unor' => Satker::find(substr($asn->siap?->SatkerID, 0, 6)),
        ]);
    }
}
