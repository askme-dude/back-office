<?php

use App\Http\Requests\PegawaiAlamatRequest;
use App\Models\Pegawai;
use App\Models\PegawaiAlamat;
use App\Models\Propinsi;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('handling crud pegawai alamat', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('can render pegawai alamat list page', function () {
        PegawaiAlamat::factory()->count(30)->create();

        $this->actingAs($this->user)
            ->get(route('alamat.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/PegawaiAlamat/Index')
                ->has('pegawaiAlamat.data', 15, fn(Assert $page) => $page
                    ->has('propinsi.nama')
                    ->has('kota.nama')
                    ->has('kecamatan.nama')
                    ->has('desa.nama')
                    ->has('pegawai.nama')
                    ->has('tipe_alamat')
                    ->has('alamat')
                    ->etc())
            );
    });

    it('can render create pegawai alamat page', function () {
        Propinsi::factory()->count(30)->create();
        Pegawai::factory()->count(50)->create();

        $this->actingAs($this->user)
            ->get(route('alamat.create'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/PegawaiAlamat/Create')
                ->has('propinsi', 30)
                ->has('pegawai', 50)
            );
    });

    it('can handle pegawai alamat store request', function () {
        PegawaiAlamatRequest::fake();

        $response = $this->actingAs($this->user)->post(route('alamat.store'));

        $alamat = PegawaiAlamat::first();

        expect($alamat)->not->toBeNull();

        $response->assertRedirect(route('alamat.index'))
            ->assertSessionHas('toast', ['message' => 'Data alamat berhasil disimpan']);
    });

    it('can render pegawai edit page', function () {
        $alamat = PegawaiAlamat::factory()->create();

        $this->actingAs($this->user)
            ->get(route('alamat.edit', $alamat))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/PegawaiAlamat/Edit')
                ->where('pegawaiAlamat.id', $alamat->id)
                ->has('pegawai')
                ->has('propinsi')
                ->has('kota')
                ->has('kecamatan')
                ->has('desa')
            );
    });

    it('can handle pegawai alamat update request', function () {
        $alamat = PegawaiAlamat::factory()->create();

        PegawaiAlamatRequest::fake();

        $this->actingAs($this->user)
            ->put(route('alamat.update', $alamat))
            ->assertRedirect(route('alamat.index'))
            ->assertSessionHas('toast', ['message' => 'Data alamat berhasil diubah']);
    });

    it('can handle pegawai alamat delete request', function () {
        $alamat = PegawaiAlamat::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('alamat.destroy', $alamat));

        $this->assertModelMissing($alamat);

        $response->assertRedirect(route('alamat.index'))
            ->assertSessionHas('toast', ['message' => 'Data alamat berhasil dihapus']);
    });
});
