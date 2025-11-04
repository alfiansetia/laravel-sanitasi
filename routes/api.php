<?php

use App\Http\Controllers\Api\SpaldtController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::delete('spaldts', [SpaldtController::class, 'destroy_batch'])
    ->name('api.spaldts.destroy_batch');
Route::apiResource('spaldts', SpaldtController::class)
    ->names('api.spaldts');
