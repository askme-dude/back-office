<?php

namespace App\Http\Controllers\Siasn;

use App\Http\Controllers\Controller;
use App\Services\SiasnSimpegService;
use Illuminate\Http\Request;

class SiasnDownloadFileController extends Controller
{
    public function __invoke(Request $request, SiasnSimpegService $service)
    {
        $data = $request->validate(['filePath' => 'required|string']);

        return response()->streamDownload(function () use ($service, $data) {
            echo $service->downloadFile($data['filePath']);
        }, 'dok-siasn.pdf');
    }
}
