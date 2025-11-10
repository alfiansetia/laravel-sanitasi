<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IpltController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SanitasiController;
use App\Http\Controllers\SpaldController;
use App\Http\Controllers\SpaldtController;
use App\Http\Controllers\TpaController;
use App\Http\Controllers\Tps3rController;
use App\Http\Controllers\TpstController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes([
    'register'  => false,
    'verify'    => false,
    'reset'     => false,
]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', [LoginController::class, 'logout']);

    Route::post('/profiles', [ProfileController::class, 'update'])
        ->name('profiles.update');
    Route::put('/profiles', [ProfileController::class, 'update_password'])
        ->name('profiles.update_password');
    Route::get('/profiles', [ProfileController::class, 'index'])
        ->name('profiles.index');

    Route::get('/', function () {
        return redirect()->route('home');
    });

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/sanitasis', [SanitasiController::class, 'index'])
        ->name('sanitasis.index');

    Route::get('/tpas', [TpaController::class, 'index'])
        ->name('tpas.index');

    Route::get('/tpsts', [TpstController::class, 'index'])
        ->name('tpsts.index');

    Route::get('/tps3r', [Tps3rController::class, 'index'])
        ->name('tps3r.index');

    Route::get('/iplts', [IpltController::class, 'index'])
        ->name('iplts.index');

    Route::get('/spalds', [SpaldController::class, 'index'])
        ->name('spalds.index');



    Route::get('/spaldts', [SpaldtController::class, 'index'])
        ->name('spaldts.index');

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');
});
