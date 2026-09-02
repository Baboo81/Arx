<?php

use App\Http\Controllers\ArxAiController;
use App\Http\Controllers\ArxServerController;
use App\Http\Controllers\ArxSocController;
use App\Http\Controllers\ArxVpnController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
|
| Ces routes sont accessibles sans authentification.
| Breeze charge notamment /login, /register, etc. via auth.php.
|
*/


/*
|--------------------------------------------------------------------------
| ARX Core
|--------------------------------------------------------------------------
|
| Toutes les routes principales de l'écosystème ARX sont protégées
| par le middleware "auth".
|
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Home
    |--------------------------------------------------------------------------
    */

    Route::get('/', [HomeController::class, 'index'])
        ->name('home');


    /*
    |--------------------------------------------------------------------------
    | Modules ARX
    |--------------------------------------------------------------------------
    */

    Route::get('/arx/ai', [ArxAiController::class, 'show'])
        ->name('arx.ai');

    Route::get('/arx/server', [ArxServerController::class, 'show'])
        ->name('arx.server');

    Route::get('/arx/soc', [ArxSocController::class, 'show'])
        ->name('arx.soc');

    Route::get('/arx/vpn', [ArxVpnController::class, 'show'])
        ->name('arx.vpn');


    /*
    |--------------------------------------------------------------------------
    | Profil utilisateur
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Authentification Breeze
|--------------------------------------------------------------------------
|
| Ce fichier contient les routes login, logout, register,
| mot de passe oublié, réinitialisation, etc.
|
*/

require __DIR__.'/auth.php';