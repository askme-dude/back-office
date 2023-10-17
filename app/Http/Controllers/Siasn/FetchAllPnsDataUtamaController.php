<?php

namespace App\Http\Controllers\Siasn;

use App\Enums\ToastIcon;
use App\Http\Controllers\Controller;
use App\Jobs\GetSiasnPnsDataUtamaJob;
use App\Services\SiapService;
use Saloon\Exceptions\Request\Statuses\NotFoundException;

class FetchAllPnsDataUtamaController extends Controller
{
    public function __invoke()
    {
        foreach (SiapService::getAllNip() as $nip) {
            try {
                GetSiasnPnsDataUtamaJob::dispatch($nip);
            } catch (NotFoundException $exception) {
                continue;
            }
        }

        return back()->with('toast', [
            'icon' => ToastIcon::SUCCESS,
            'message' => 'Sinkronisasi data sedang diproses',
        ]);
    }
}
