<?php

use App\Models\Siasn\SiasnPnsDataUtama;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

it('it can render pns siasn list page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('siasn-asn.index'))
        ->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Siasn/Asn/Index'));
});

it('can render a pns siasn page', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->create();

    $pns = SiasnPnsDataUtama::factory()->create();

    $this->actingAs($user)
        ->get(route('siasn-asn.show', $pns->id))
        ->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Siasn/Asn/Show')
            ->has('siasn')
//            ->has('siap.pangkatTerakhir')
//            ->has('siap.agama')
        );
})->skip(fn() => \Illuminate\Support\Facades\DB::getDriverName() !== 'sqlsrv');
