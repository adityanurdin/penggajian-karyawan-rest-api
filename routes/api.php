<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\GajiPegawaiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Pegawai Endpoint

# GET
Route::get('pegawai', [PegawaiController::class, 'index']);
# POST
Route::post('pegawai', [PegawaiController::class, 'store']);

// Gaji Pegawai Endpoind

# GET
Route::get('gaji-pegawai', [GajiPegawaiController::class, 'index']);
# POST
Route::post('gaji-pegawai', [GajiPegawaiController::class, 'store']);
# POST
Route::post('gaji-pegawai/batch', [GajiPegawaiController::class, 'store_batch']);

