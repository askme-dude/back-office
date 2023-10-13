<?php

use App\Http\Requests\PegawaiRiwayatPenghargaanRequest;
use App\Models\Pegawai;
use App\Models\PegawaiRiwayatPenghargaan;
use App\Models\Penghargaan;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

it('can render riwayat penghargaan list page', function () {
    $user = User::factory()->create();
    PegawaiRiwayatPenghargaan::factory()->count(20)->create();

    $this->actingAs($user)
        ->get(route('riwayat-penghargaan.index'))
        ->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Pegawai/PegawaiRiwayatPenghargaan/Index')
            ->has('riwayat.data', 15, fn(AssertableInertia $page) => $page
                ->has('pegawai.nama')
                ->has('penghargaan.nama')
                ->etc())
        );
});

it('can render riwayat penghargaan create page', function () {
    $user = User::factory()->create();
    Pegawai::factory()->count(20)->create();
    Penghargaan::factory()->count(5)->create();

    $this->actingAs($user)
        ->get(route('riwayat-penghargaan.create'))
        ->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Pegawai/PegawaiRiwayatPenghargaan/Create')
            ->has('pegawai', 10)
            ->has('penghargaan', 5)
        );
});

it('can handle riwayat penghargaan store request', function () {
    $user = User::factory()->create();

    PegawaiRiwayatPenghargaanRequest::fake();

    $this->actingAs($user)
        ->post(route('riwayat-penghargaan.store'))
        ->assertRedirect(route('riwayat-penghargaan.index'))
        ->assertSessionHas('toast', ['message' => 'Data berhasil disimpan']);

    $this->assertDatabaseCount(PegawaiRiwayatPenghargaan::class, 1);

    $riwayat = PegawaiRiwayatPenghargaan::first();
    $penghargaan = Penghargaan::first();
    $pegawai = Pegawai::first();

    expect($riwayat)
        ->media->toHaveCount(1)
        ->penghargaan->is($penghargaan)->toBeTrue()
        ->pegawai->is($pegawai)->toBeTrue();
});

it('can render riwayat penghargaan edit page', function () {
    $user = User::factory()->create();
    $riwayat = PegawaiRiwayatPenghargaan::factory()->create();

    $this->actingAs($user)
        ->get(route('riwayat-penghargaan.edit', $riwayat))
        ->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Pegawai/PegawaiRiwayatPenghargaan/Edit')
            ->has('pegawai')
            ->has('currentPegawai')
            ->has('penghargaan')
            ->has('riwayat')
        );
});

it('can handle riwayat penghargaan update request', function () {
    $user = User::factory()->create();
    $riwayat = PegawaiRiwayatPenghargaan::factory()->create();

    PegawaiRiwayatPenghargaanRequest::fake();

    $this->actingAs($user)
        ->put(route('riwayat-penghargaan.update', $riwayat))
        ->assertRedirect(route('riwayat-penghargaan.index'))
        ->assertSessionHas('toast', ['message' => 'Data berhasil disimpan']);

    expect($riwayat->fresh())->getMedia('media_sk')->toHaveCount(1);
});

it('can handle riwayat penghargaan delete request', function () {
    $user = User::factory()->create();

    $riwayat = PegawaiRiwayatPenghargaan::factory()->create();

    $this->actingAs($user)
        ->delete(route('riwayat-penghargaan.destroy', $riwayat))
        ->assertRedirect(route('riwayat-penghargaan.index'))
        ->assertSessionHas('toast', ['message' => 'Data berhasil dihapus']);

    $this->assertModelMissing($riwayat);
});
