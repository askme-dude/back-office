<?php

use App\Models\Tukin;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('tunjangan kinerja controller', function () {

    it('can render tukin list page', function () {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('tukin.index'))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('Tukin/Index')
                ->has('list_tukin')
            );
    });

    it('can handle tukin store request', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('tukin.store'), [
                'grade' => 8,
                'nominal' => 10_000
            ]);

        expect(Tukin::first())
            ->grade->toEqual(8)
            ->nominal->toEqual(10_000);

        $response->assertRedirect()
            ->assertSessionHas('toast');
    });

    it('can validate tukin store request', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('tukin.store'), [
                'grade' => 8,
                'nominal' => 'abc'
            ]);

        $response->assertSessionHasErrors();
    });

    it('can handle tukin update request', function () {
        $user = User::factory()->create();
        $tukin = Tukin::factory()->create();

        $response = $this->actingAs($user)
            ->put(route('tukin.update', $tukin), [
                'grade' => 8,
                'nominal' => 5_000
            ]);

        expect($tukin->fresh())
            ->grade->toEqual(8)
            ->nominal->toEqual(5_000);

        $response->assertRedirect()->assertSessionHas('toast');
    });

    it('can handle tukin delete request', function () {
        $user = User::factory()->create();
        $tukin = Tukin::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('tukin.destroy', $tukin));

        $this->assertModelMissing($tukin);
        $response->assertRedirect()->assertSessionHas('toast');
    });
})->todo();
