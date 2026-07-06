<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/********************/
/* Routes globales */
/*******************/
Route::get('/', [HomeController::class, 'index'])->name('home');
