<?php

use App\Http\Requests\PegawaiRiwayatPendidikanRequest;
use App\Models\Pegawai;
use App\Models\PegawaiRiwayatPendidikan;
use App\Models\Pendidikan;
use App\Models\Propinsi;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('handling crud pegawai riwayat pendidikan', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('can render riwayat pendidikan list page', function () {
        PegawaiRiwayatPendidikan::factory()->count(20)->create();

        $this->actingAs($this->user)
            ->get(route('riwayat-pendidikan.index'))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('Pegawai/PegawaiRiwayatPendidikan/Index')
                ->has('riwayatPendidikan.data', 15, fn(AssertableInertia $page) => $page
                    ->has('pegawai.nama')
                    ->has('pendidikan.nama')
                    ->has('nama_instansi')
                    ->has('no_ijazah')
                    ->has('tanggal_ijazah')
                    ->etc())
            );
    });

    it('can render riwayat pendidikan create page', function () {
        Pegawai::factory()->count(20)->create();
        Pendidikan::factory()->count(20)->create();
        Propinsi::factory()->count(30)->create();

        $this->actingAs($this->user)
            ->get(route('riwayat-pendidikan.create'))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('Pegawai/PegawaiRiwayatPendidikan/Create')
                ->has('pegawai', 10)
                ->has('pendidikan', 10)
                ->has('propinsi', 30)
            );
    });

    it('can handle riwayat pendidikan store request', function () {
        PegawaiRiwayatPendidikanRequest::fake();

        $response = $this->actingAs($this->user)
            ->post(route('riwayat-pendidikan.store'));

        expect(PegawaiRiwayatPendidikan::first())->not->toBeNull()
            ->media->toHaveCount(1);

        $response->assertRedirect(route('riwayat-pendidikan.index'))
            ->assertSessionHas('toast', ['message' => 'Riwayat pendidikan berhasil disimpan']);
    });

    it('can render riwayat pendidikan edit page', function () {
        $riwayat = PegawaiRiwayatPendidikan::factory()->create();

        Pegawai::factory()->count(20)->create();
        Pendidikan::factory()->count(20)->create();
        Propinsi::factory()->count(30)->create();

        $this->actingAs($this->user)
            ->get(route('riwayat-pendidikan.edit', $riwayat))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('Pegawai/PegawaiRiwayatPendidikan/Edit')
                ->where('riwayatPendidikan.id', $riwayat->id)
                ->where('currentPegawai.nama', $riwayat->pegawai->nama)
                ->has('pegawai', 10)
                ->has('pendidikan', 10)
                ->has('propinsi', 32)
            );
    });

    it('can handle riwayat pendidikan update request', function ($attr, $value) {
        $riwayat = PegawaiRiwayatPendidikan::factory()->create();

        PegawaiRiwayatPendidikanRequest::fake([$attr => $value]);

        $response = $this->actingAs($this->user)
            ->put(route('riwayat-pendidikan.update', $riwayat));

        expect($riwayat->fresh()->$attr)->toEqual($value);

        $response->assertRedirect(route('riwayat-pendidikan.index'))
            ->assertSessionHas('toast', ['message' => 'Riwayat pendidikan berhasil diubah']);
    })->with([
        ['nama_instansi', 'ganti nama']
    ]);

    it('can handle riwayat pendidikan delete request', function () {
        $riwayat = PegawaiRiwayatPendidikan::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('riwayat-pendidikan.destroy', $riwayat));

        $this->assertModelMissing($riwayat);

        $response->assertRedirect(route('riwayat-pendidikan.index'))
            ->assertSessionHas('toast', ['message' => 'Riwayat pendidikan berhasil dihapus']);
    });
});
