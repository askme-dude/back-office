<?php

use App\Models\Siasn\SiasnPnsRwPenghargaan;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

beforeEach(fn() => $this->user = User::factory()->create());

it('can render siasn riwayat penghargaan list page', function () {
    SiasnPnsRwPenghargaan::factory()->count(20)->create();

    $this->actingAs($this->user)
        ->get(route('siasn-penghargaan.index'))
        ->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Siasn/Penghargaan/Index')
            ->has('penghargaan.data', 15)
        );
});
