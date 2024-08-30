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

    // Programs
    Route::get('programs', 'ProgramController@index')->name('programs.index');
    Route::get('programs/{program:slug}', 'ProgramController@show')->name('programs.show');

    // Projects
    Route::get('projects', 'ProjectController@index')->name('projects.index');
    Route::get('projects/{project:slug}', 'ProjectController@show')->name('projects.show');

    // Success stories
    Route::get('stories', 'StoryController@index')->name('stories.index');
    Route::get('stories/{story:slug}', 'StoryController@show')->name('stories.show');

    // Announcements
    Route::get('announcements', 'AnnouncementController@index')->name('announcements.index');
    Route::get('announcements/{announcement}', 'AnnouncementController@show')->name('announcements.show');

    // Galleries
    Route::get('galleries', 'GalleryController@index')->name('galleries.index');
    Route::get('galleries/{gallery:slug}', 'GalleryController@show')->name('galleries.show');
    
    // Documents
    Route::get('documents', 'DocumentController@index')->name('documents.index');
    Route::get('documents/{document:slug}', 'DocumentController@show')->name('documents.show');

    // Videos
    Route::get('videos', 'VideoController@index')->name('videos.index');
    Route::get('videos/{video:slug}', 'VideoController@show')->name('videos.show');

    // Store contact us
    Route::post('contact-us', 'MailController@storeContactUs')->name('contact_us.store');

    // Page route: must be the last one
    Route::get('/{page:slug}', 'PageController@show')->name('pages.show');
});