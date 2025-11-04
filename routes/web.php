<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpaldtController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes([
    'register'  => false,
    'verify'    => false,
    'reset'     => false,
]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', [LoginController::class, 'logout']);

    Route::get('/', function () {
        return redirect()->route('home');
    });

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('/spaldts', SpaldtController::class)
        ->names('spaldts');
});
