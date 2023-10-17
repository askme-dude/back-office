<?php

namespace App\Http\Controllers\Siasn;

use App\Enums\ToastIcon;
use App\Http\Controllers\Controller;
use App\Services\SiasnSimpegService;
use Illuminate\Http\Request;

class FetchPnsDataUtamaController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate(['nip' => ['required', 'numeric']]);

        $siasn = (new SiasnSimpegService())->fetchPnsDataUtama($validated['nip']);

        return back()->with('toast', [
            'icon' => ToastIcon::SUCCESS,
            'message' => "Sinkronisasi Data $siasn->nama berhasil"
        ]);
    }
}
