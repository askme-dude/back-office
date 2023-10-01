<?php

use App\Http\Controllers\Api\PendidikanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// move to middleware auth sanctum
Route::prefix('ref')->group(function () {
    Route::get('pendidikan', [PendidikanController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {

});
