<?php

namespace App\Http\Controllers\Siap;

use App\Enums\ToastIcon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Siap\UpdatePegawaiRequest;
use App\Integration\Siap\Model\Pegawai;

class UpdatePegawaiController extends Controller
{
    public function __invoke(UpdatePegawaiRequest $request, Pegawai $pegawai)
    {
        $data = $request->validated();

        $pegawai->update([
            $data['attribute'] => $data['value']
        ]);

        return back()->with('toast', [
            'icon' => ToastIcon::SUCCESS,
            'message' => 'Data SIAP telah berhasil diperbarui'
        ]);
    }
}
