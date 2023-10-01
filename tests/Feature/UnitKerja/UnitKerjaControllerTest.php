<?php

use App\Http\Requests\UnitKerjaRequest;
use App\Models\JenisUnitKerja;
use App\Models\UnitKerja;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('handling crud unit kerja', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('can render unit kerja list', function () {
        UnitKerja::factory()->count(30)->create();

        $this->actingAs($this->user)
            ->get(route('unit-kerja.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('UnitKerja/Index')
                ->has('unitKerja.data', 15, fn(Assert $page) => $page
                    ->has('parent')
                    ->etc())
            );
    });

    it('can render unit kerja create page', function () {
        [$jenis] = JenisUnitKerja::factory()->count(5)->create();

        UnitKerja::factory()->recycle($jenis)->count(20)->create();

        $this->actingAs($this->user)
            ->get(route('unit-kerja.create'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('UnitKerja/Create')
                ->has('unitOptions', 10)
                ->has('jenisUnitKerja', 5)
            );
    });

    it('can handle unit kerja store request', function () {
        UnitKerjaRequest::fake();

        $this->actingAs($this->user)
            ->post(route('unit-kerja.store'))
            ->assertRedirect(route('unit-kerja.index'))
            ->assertSessionHas('toast', ['message' => 'Data unit kerja berhasil disimpan']);

        expect(UnitKerja::first())->not->toBeNull();
    });

    it('can render unit kerja edit page', function () {
        [$jenis] = JenisUnitKerja::factory()->count(5)->create();

        [$unit] = UnitKerja::factory()->recycle($jenis)->count(20)->create();

        $this->actingAs($this->user)
            ->get(route('unit-kerja.edit', $unit))
            ->assertInertia(fn(Assert $page) => $page
                ->component('UnitKerja/Edit')
                ->where('currentUnit.nama', $unit->nama)
                ->has('unitOptions', 10)
                ->has('jenisUnitKerja', 5)
            );
    });

    it('can handle unit kerja update request', function () {
        $unit = UnitKerja::factory()->create();

        UnitKerjaRequest::fake();

        $this->actingAs($this->user)
            ->put(route('unit-kerja.update', $unit))
            ->assertRedirect(route('unit-kerja.index'))
            ->assertSessionHas('toast', ['message' => 'Data unit kerja berhasil diubah']);
    });

    it('can handle unit kerja delete request', function () {
        $unit = UnitKerja::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('unit-kerja.destroy', $unit))
            ->assertRedirect(route('unit-kerja.index'))
            ->assertSessionHas('toast', ['message' => 'Data unit kerja berhasil dihapus']);

        $this->assertModelMissing($unit);
    });
});
