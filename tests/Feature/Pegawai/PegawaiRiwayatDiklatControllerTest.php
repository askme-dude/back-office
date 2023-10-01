<?php

use App\Http\Requests\PegawaiRiwayatDiklatRequest;
use App\Models\JenisDiklat;
use App\Models\Pegawai;
use App\Models\PegawaiRiwayatDiklat;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('handling crud pegawai riwayat diklat', function () {
    beforeEach(fn() => $this->user = User::factory()->create());

    it('can render riwayat diklat list page', function () {
        PegawaiRiwayatDiklat::factory()->count(20)->create();

        $this->actingAs($this->user)
            ->get(route('riwayat-diklat.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/PegawaiRiwayatDiklat/Index')
                ->has('diklat.data', 15)
            );
    });

    it('can render riwayat diklat create page', function () {
        Pegawai::factory()->count(20)->create();
        JenisDiklat::factory()->count(5)->create();

        $this->actingAs($this->user)
            ->get(route('riwayat-diklat.create'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/PegawaiRiwayatDiklat/Create')
                ->has('pegawai', 10)
                ->has('jenisDiklat', 5)
            );
    });

    it('can handle pegawai riwayat diklat store request', function () {
        PegawaiRiwayatDiklatRequest::fake();

        $response = $this->actingAs($this->user)
            ->post(route('riwayat-diklat.store'));

        expect(PegawaiRiwayatDiklat::first())->not->toBeNull()
            ->media->toHaveCount(1);

        $response->assertRedirect(route('riwayat-diklat.index'))
            ->assertSessionHas('toast', ['message' => 'Riwayat diklat berhasil disimpan']);
    });

    it('can render riwayat diklat edit page', function () {
        Pegawai::factory()->count(20)->create();
        JenisDiklat::factory()->count(5)->create();

        $diklat = PegawaiRiwayatDiklat::factory()
            ->for(Pegawai::first())
            ->for(JenisDiklat::first())
            ->create();

        $this->actingAs($this->user)
            ->get(route('riwayat-diklat.edit', $diklat))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/PegawaiRiwayatDiklat/Edit')
                ->has('pegawai', 10)
                ->has('jenisDiklat', 5)
                ->where('diklat.nama', $diklat->nama)
                ->where('currentPegawai.nama', $diklat->pegawai->nama)
                ->missing('currentPegawai.nip')
            );
    });

    it('can handle riwayat diklat update request', function ($attr, $val) {
        PegawaiRiwayatDiklatRequest::factory()->state([$attr => $val])->fake();

        $diklat = PegawaiRiwayatDiklat::factory()->create();

        $response = $this->actingAs($this->user)
            ->put(route('riwayat-diklat.update', $diklat));

        expect($diklat->fresh())->$attr->toEqual($val)
            ->media->toHaveCount(1);

        $response->assertRedirect(route('riwayat-diklat.index'))
            ->assertSessionHas('toast', ['message' => 'Riwayat diklat berhasil diubah']);
    })->with([
        ['nama', 'ganti nama diklat']
    ]);

    it('can handle riwayat diklat delete request', function () {
        $diklat = PegawaiRiwayatDiklat::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('riwayat-diklat.destroy', $diklat));

        $this->assertModelMissing($diklat);

        $response->assertRedirect(route('riwayat-diklat.index'))
            ->assertSessionHas('toast', ['message' => 'Riwayat diklat berhasil dihapus']);
    });
});
