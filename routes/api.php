<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SpaldtController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\IpltController;
use App\Http\Controllers\Api\KecamatanController;
use App\Http\Controllers\Api\KelurahanController;
use App\Http\Controllers\Api\SanitasiController;
use App\Http\Controllers\Api\SpaldController;
use App\Http\Controllers\Api\TpaController;
use App\Http\Controllers\Api\Tps3rController;
use App\Http\Controllers\Api\TpstController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::delete('users', [UserController::class, 'destroy_batch'])
        ->name('api.users.destroy_batch');
    Route::apiResource('users', UserController::class)
        ->names('api.users');

    Route::delete('kecamatans', [KecamatanController::class, 'destroy_batch'])
        ->name('api.kecamatans.destroy_batch');
    Route::post('kecamatan-imports', [KecamatanController::class, 'import'])
        ->name('api.kecamatans.import');
    Route::apiResource('kecamatans', KecamatanController::class)
        ->names('api.kecamatans');

    Route::delete('kelurahans', [KelurahanController::class, 'destroy_batch'])
        ->name('api.kelurahans.destroy_batch');
    Route::post('kelurahan-imports', [KelurahanController::class, 'import'])
        ->name('api.kelurahans.import');
    Route::apiResource('kelurahans', KelurahanController::class)
        ->names('api.kelurahans');

    Route::delete('sanitasis', [SanitasiController::class, 'destroy_batch'])
        ->name('api.sanitasis.destroy_batch');
    Route::post('sanitasi-imports', [SanitasiController::class, 'import'])
        ->name('api.sanitasis.import');
    Route::apiResource('sanitasis', SanitasiController::class)
        ->names('api.sanitasis');

    Route::delete('tpas', [TpaController::class, 'destroy_batch'])
        ->name('api.tpas.destroy_batch');
    Route::post('tpa-imports', [TpaController::class, 'import'])
        ->name('api.tpas.import');
    Route::apiResource('tpas', TpaController::class)
        ->names('api.tpas');

    Route::delete('tpsts', [TpstController::class, 'destroy_batch'])
        ->name('api.tpsts.destroy_batch');
    Route::post('tpst-imports', [TpstController::class, 'import'])
        ->name('api.tpsts.import');
    Route::apiResource('tpsts', TpstController::class)
        ->names('api.tpsts');

    Route::delete('tps3rs', [Tps3rController::class, 'destroy_batch'])
        ->name('api.tps3rs.destroy_batch');
    Route::post('tps3r-imports', [Tps3rController::class, 'import'])
        ->name('api.tps3rs.import');
    Route::apiResource('tps3rs', Tps3rController::class)
        ->names('api.tps3rs');

    Route::delete('iplts', [IpltController::class, 'destroy_batch'])
        ->name('api.iplts.destroy_batch');
    Route::post('iplt-imports', [IpltController::class, 'import'])
        ->name('api.iplts.import');
    Route::apiResource('iplts', IpltController::class)
        ->names('api.iplts');

    Route::delete('spalds', [SpaldController::class, 'destroy_batch'])
        ->name('api.spalds.destroy_batch');
    Route::post('spaldt-imports', [SpaldController::class, 'import'])
        ->name('api.spalds.import');
    Route::apiResource('spalds', SpaldController::class)
        ->names('api.spalds');

    Route::get('dashboards', [DashboardController::class, 'index'])
        ->name('api.dashboards.index');
});
