<?php

use App\Http\Controllers\ArxAiController;
use App\Http\Controllers\ArxServerController;
use App\Http\Controllers\ArxSocController;
use App\Http\Controllers\ArxVpnController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/********************/
/* Routes globales */
/*******************/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('arx.ai', [ArxAiController::class, 'show'])->name('arx.ai');
Route::get('arx.server', [ArxServerController::class, 'show'])->name('arx.server');
Route::get('arx.soc', [ArxSocController::class, 'show'])->name('arx.soc');
Route::get('arx.vpn', [ArxVpnController::class, 'show'])->name('arx.vpn');
