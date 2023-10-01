<?php

use App\Enums\GolonganDarah;
use App\Http\Requests\PegawaiRequest;
use App\Models\Agama;
use App\Models\JenisKawin;
use App\Models\JenisPegawai;
use App\Models\Pegawai;
use App\Models\StatusPegawai;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('handling crud pegawai', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('can render pegawai list page', function () {
        Pegawai::factory()->count(20)->create();

        $this->actingAs($this->user)
            ->get(route('pegawai.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/Index')
                ->has('pegawai.data', 15));
    });

    it('can render pegawai create page', function () {
        Agama::factory()->count(4)->create();
        JenisKawin::factory()->count(4)->create();
        JenisPegawai::factory()->count(4)->create();
        StatusPegawai::factory()->count(4)->create();

        $this->actingAs($this->user)
            ->get(route('pegawai.create'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/Create')
                ->has('agama', 4)
                ->has('statusNikah', 4)
                ->has('jenisPegawai', 4)
                ->has('statusPegawai', 4)
                ->has('golonganDarah', GolonganDarah::count())
            );
    });

    it('can handle pegawai store request', function () {
        PegawaiRequest::fake();

        $response = $this->actingAs($this->user)
            ->post(route('pegawai.store'));

        expect($pegawai = Pegawai::first())->not->toBeNull();

        $response->assertRedirect(route('pegawai.show', $pegawai))
            ->assertSessionHas('toast', ['message' => 'Data pegawai berhasil disimpan']);
    });

    it('can render a pegawai show page', function () {
        $pegawai = Pegawai::factory()->create();

        $this->actingAs($this->user)
            ->get(route('pegawai.show', $pegawai))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/Show')
                ->where('pegawai.id', $pegawai->id)
                ->where('pegawai.nama', $pegawai->nama)
                ->has('media_foto_pegawai')
                ->has('media_kartu_pegawai')
            );
    });

    it('can render pegawai edit page', function () {
        $pegawai = Pegawai::factory()->create();

        Agama::factory()->count(4)->create();
        JenisKawin::factory()->count(4)->create();
        JenisPegawai::factory()->count(4)->create();
        StatusPegawai::factory()->count(4)->create();

        $this->actingAs($this->user)
            ->get(route('pegawai.edit', $pegawai))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Pegawai/Edit')
                ->where('pegawai.id', $pegawai->id)
                ->where('pegawai.nama', $pegawai->nama)
                ->has('agama', 5)
                ->has('statusNikah', 5)
                ->has('jenisPegawai', 5)
                ->has('statusPegawai', 5)
                ->has('media_foto_pegawai')
                ->has('media_kartu_pegawai')
                ->has('golonganDarah', GolonganDarah::count())
            );
    });

    it('can handle pegawai update request', function ($attribute, $value) {
        $pegawai = Pegawai::factory()->create();

        PegawaiRequest::fake([$attribute => $value]);

        $this->actingAs($this->user)
            ->put(route('pegawai.update', $pegawai))
            ->assertRedirect(route('pegawai.show', $pegawai))
            ->assertSessionHas('toast', ['message' => 'Data pegawai berhasil disimpan']);

        expect($pegawai->fresh()->$attribute)->toEqual($value);
    })->with([
        ['nik', fake()->numerify('################')],
        ['nip', fake()->numerify('##################')],
        ['nama', fake()->name()],
        ['jenis_kelamin', fake()->randomElement(['L', 'P'])],
        ['golongan_darah', fake()->randomElement(['O', 'A', 'B', 'AB'])],
        ['tempat_lahir', fake()->city()],
        ['tanggal_lahir', fake()->dateTimeBetween('1990-01-01', '2012-12-31')->format('Y-m-d')],
        ['email_kantor', fake()->word() . '@office.com'],
        ['email_pribadi', fake()->word() . '@google.com'],
        ['no_telp', fake()->numerify('#############')],
        ['status_dinas', fake()->boolean()],
        ['no_bpjs', fake()->numerify('#############')],
        ['no_kartu_pegawai', fake()->numerify('#########')],
    ]);

    it('can handle delete pegawai request', function () {
        $pegawai = Pegawai::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('pegawai.destroy', $pegawai))
            ->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'Data pegawai berhasil dihapus']);

        $this->assertModelMissing($pegawai);
    });
});

describe('validate attribute pegawai form request', function () {
    beforeEach(fn() => $this->user = User::factory()->create());

    it('can validate missing attribute in pegawai store request', function ($attribute) {
        PegawaiRequest::factory()->without($attribute)->fake();

        $this->actingAs($this->user)
            ->post(route('pegawai.store'))
            ->assertSessionHasErrors([$attribute]);
    })->with([
        'nik', 'nip', 'nama', 'jenis_kelamin', 'agama_id', 'jenis_kawin_id', 'jenis_pegawai_id', 'status_pegawai_id',
        'tempat_lahir', 'tanggal_lahir', 'email_kantor', 'no_telp', 'status_dinas', 'no_bpjs'
    ]);

    it('can process pegawai store request with missing attribute', function ($attribute) {
        PegawaiRequest::factory()->without($attribute)->fake();

        $this->actingAs($this->user)
            ->post(route('pegawai.store'))
            ->assertSessionDoesntHaveErrors($attribute);
    })->with([
        'email_pribadi', 'golongan_darah', 'tanggal_wafat', 'tanggal_berhenti', 'no_taspen', 'npwp',
        'no_enroll', 'no_kartu_pegawai', 'media_kartu_pegawai', 'media_foto_pegawai'
    ]);

    it('can validate missing attribute in pegawai update request ', function ($attribute) {
        $pegawai = Pegawai::factory()->create();

        PegawaiRequest::factory()->without($attribute)->fake();

        $this->actingAs($this->user)
            ->put(route('pegawai.update', $pegawai))
            ->assertSessionHasErrors($attribute);
    })->with([
        'nik', 'nip', 'nama', 'jenis_kelamin', 'agama_id', 'jenis_kawin_id', 'jenis_pegawai_id', 'status_pegawai_id',
        'tempat_lahir', 'tanggal_lahir', 'email_kantor', 'no_telp', 'status_dinas', 'no_bpjs', 'no_kartu_pegawai',
    ]);

    it('can process pegawai update request with missing attribute', function ($attribute) {
        $pegawai = Pegawai::factory()->create();

        PegawaiRequest::factory()->without($attribute)->fake();

        $this->actingAs($this->user)
            ->put(route('pegawai.update', $pegawai))
            ->assertSessionDoesntHaveErrors($attribute);
    })->with([
        'email_pribadi', 'golongan_darah', 'tanggal_wafat', 'tanggal_berhenti', 'no_taspen', 'npwp',
        'no_enroll', 'media_kartu_pegawai', 'media_foto_pegawai'
    ]);
});
