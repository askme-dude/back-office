<?php

use App\Integration\Siasn\Request\Simpeg\GetPnsDataUtamaRequest;
use App\Models\Siasn\SiasnPnsDataUtama;
use App\Models\User;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;

it('can handle request to fetch pns data from siasn', function () {
    $this->withoutExceptionHandling();

    \Saloon\Laravel\Facades\Saloon::fake([
        '*' => function (PendingRequest $request) {
            $reflection = new ReflectionClass($request->getRequest());

            return MockResponse::fixture('Simpeg/' . $reflection->getShortName());
        },
    ]);

    $user = User::factory()->create();

    $siasn = SiasnPnsDataUtama::factory()->create(['nipBaru' => 199111182019031005]);

    $response = $this->actingAs($user)
        ->post(route('fetch-pns-data-utama'), ['nip' => $siasn->nipBaru]);

    \Saloon\Laravel\Facades\Saloon::assertSent(GetPnsDataUtamaRequest::class);

    $response->assertRedirect();
});
