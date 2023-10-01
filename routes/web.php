<?php

use App\Http\Controllers\Auth\LdapController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\JabatanTukinController;
use App\Http\Controllers\JabatanUnitKerjaController;
use App\Http\Controllers\Master\HariLiburController;
use App\Http\Controllers\Master\HirarkiUnitKerjaController;
use App\Http\Controllers\Master\KotaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TukinController;
use App\Http\Controllers\UangMakanController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::resource('tukin', TukinController::class)->only('index', 'create', 'store', 'edit', 'update', 'destroy');
Route::resource('umak', UangMakanController::class)->only('index', 'create', 'store', 'edit', 'update', 'destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('gaji', GajiController::class)->only('index', 'create', 'edit', 'store', 'update', 'destroy');
    Route::resource('jabatan-tukin', JabatanTukinController::class)->only('index', 'create', 'edit', 'store', 'update', 'destroy');
    Route::get('/jabatan-tukin/getjabatan/{id}', [JabatanTukinController::class, 'getjabatan']);
    Route::get('/jabatan-tukin/getjenisjabatan', [JabatanTukinController::class, 'getJenisJabatan']);
    Route::get('/jabatan-tukin/gettukin', [JabatanTukinController::class, 'getTukin']);
    Route::get('/jabatan-tukin/getnominal/{id}', [JabatanTukinController::class, 'getNominal']);

    Route::resource('jabatan-unit-kerja', JabatanUnitKerjaController::class)->only('index', 'create', 'edit', 'store', 'update', 'destroy');

    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('user', UserController::class);
});

Route::prefix('master')->group(function () {
    Route::post('kota/getdata', [KotaController::class, 'getKota'])->name('kota.getdata');
    Route::get('hari-libur/getdata', [HariLiburController::class, 'getDataHariLibur'])->name('hari-libur.getdata');
    Route::resource('hari-libur', HariLiburController::class)->except('show');
});

Route::resource('unit-kerja', UnitKerjaController::class)->except('show');

require __DIR__ . '/pegawai.php';

require __DIR__ . '/siap.php';

require __DIR__ . '/siasn.php';

require __DIR__ . '/auth.php';

Route::middleware('guest')->group(function () {
    Route::get('/login/ldap', [LdapController::class, 'showLoginForm'])->name('loginldap.show');
    Route::post('/login/ldap', [LdapController::class, 'login'])->name('login.ldap');
});
Route::get('/logout/ldap', [LdapController::class, 'logout'])->name('logout.ldap');
