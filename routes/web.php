<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('login', 'login')->name('login');
Route::post('login', [AuthController::class, 'login'])->name('loginPost');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
