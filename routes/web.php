<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::view('login', 'login')->name('login');
Route::post('login', [AuthController::class, 'login'])->name('loginPost');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::group([
    'namespace' => 'App\Http\Controllers',
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']
], function() {
    // Home
    Route::get('/', 'HomeController@index')->name('home');

    // News
    Route::get('news', 'NewsController@index')->name('news.index');
    Route::get('news/{news:slug}', 'NewsController@show')->name('news.show');
});