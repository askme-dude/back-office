<?php

use App\Models\Golongan;
use App\Models\UangMakan;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('uang makan controller', function () {

    it('can render uang makan list page', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('umak.index'))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('Umak/Index')
                ->has('uang_makan'));
    });

    it('can handle uang makan store page', function () {
        $user = User::factory()->create();
        $golongan = Golongan::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('umak.store'), [
                'golongan_id' => $golongan->id,
                'nominal' => 50_000
            ]);

        expect(UangMakan::first())
            ->golongan_id->toEqual(1)
            ->nominal->toEqual(50_000);

        $response->assertRedirect()->assertSessionHas('toast');
    });

    it('can handle uang makan update page', function () {
        $user = User::factory()->create();
        $umak = UangMakan::factory()->create();
        $golongan = Golongan::factory()->create();

        $response = $this->actingAs($user)
            ->put(route('umak.update', $umak), [
                'golongan_id' => $golongan->id,
                'nominal' => 50_000
            ]);

        expect($umak->fresh())
            ->golongan_id->toEqual($golongan->id)
            ->nominal->toEqual(50_000);

        $response->assertRedirect()->assertSessionHas('toast');
    });

    it('can handle uang makan delete request', function () {
        $user = User::factory()->create();
        $umak = UangMakan::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('umak.update', $umak));

        $this->assertModelMissing($umak);

        $response->assertRedirect()->assertSessionHas('toast');
    });
})->todo();
