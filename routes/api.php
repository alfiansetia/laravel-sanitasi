<?php

use App\Http\Controllers\Api\SpaldtController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\IpltController;
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

    Route::delete('sanitasis', [SanitasiController::class, 'destroy_batch'])
        ->name('api.sanitasis.destroy_batch');
    Route::post('spaldt-imports', [SanitasiController::class, 'import'])
        ->name('api.sanitasis.import');
    Route::apiResource('sanitasis', SanitasiController::class)
        ->names('api.sanitasis');

    Route::delete('tpas', [TpaController::class, 'destroy_batch'])
        ->name('api.tpas.destroy_batch');
    Route::post('spaldt-imports', [TpaController::class, 'import'])
        ->name('api.tpas.import');
    Route::apiResource('tpas', TpaController::class)
        ->names('api.tpas');

    Route::delete('tpsts', [TpstController::class, 'destroy_batch'])
        ->name('api.tpsts.destroy_batch');
    Route::post('spaldt-imports', [TpstController::class, 'import'])
        ->name('api.tpsts.import');
    Route::apiResource('tpsts', TpstController::class)
        ->names('api.tpsts');

    Route::delete('tps3r', [Tps3rController::class, 'destroy_batch'])
        ->name('api.tps3r.destroy_batch');
    Route::post('spaldt-imports', [Tps3rController::class, 'import'])
        ->name('api.tps3r.import');
    Route::apiResource('tps3r', Tps3rController::class)
        ->names('api.tps3r');

    Route::delete('iplts', [IpltController::class, 'destroy_batch'])
        ->name('api.iplts.destroy_batch');
    Route::post('spaldt-imports', [IpltController::class, 'import'])
        ->name('api.iplts.import');
    Route::apiResource('iplts', IpltController::class)
        ->names('api.iplts');

    Route::delete('spalds', [SpaldController::class, 'destroy_batch'])
        ->name('api.spalds.destroy_batch');
    Route::post('spaldt-imports', [SpaldController::class, 'import'])
        ->name('api.spalds.import');
    Route::apiResource('spalds', SpaldController::class)
        ->names('api.spalds');

    Route::delete('spaldts', [SpaldtController::class, 'destroy_batch'])
        ->name('api.spaldts.destroy_batch');
    Route::post('spaldt-imports', [SpaldtController::class, 'import'])
        ->name('api.spaldts.import');
    Route::apiResource('spaldts', SpaldtController::class)
        ->names('api.spaldts');
});
