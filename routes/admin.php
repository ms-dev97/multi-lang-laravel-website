<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth',
    'namespace' => 'App\Http\Controllers\Admin'
], function() {
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('dashboard', 'HomeController@index')->name('dashboard');
    
    // Categories routes
    Route::resource('categories', CategoryController::class);

    // News routes
    Route::get('news/search', 'NewsController@search')->name('news.search');
    Route::get('news/export', 'NewsController@exportExcel')->name('news.export');
    Route::resource('news', NewsController::class);

    // Program routes
    Route::get('programs/search', 'ProgramController@search')->name('programs.search');
    Route::resource('programs', ProgramController::class);

    // Project routes
    Route::get('projects/search', 'ProjectController@search')->name('projects.search');
    Route::resource('projects', ProjectController::class);

    // Announcements Routes
    Route::resource('announcement-categories', AnnouncementCategoryController::class);
    Route::get('announcements/search', 'AnnouncementController@search')->name('announcements.search');
    Route::resource('announcements', AnnouncementController::class);

    // Document routes
    Route::resource('document-categories', DocumentCategoryController::class);
    Route::get('documents/search', 'DocumentController@search')->name('documents.search');
    Route::resource('documents', DocumentController::class);

    // Gallery routes
    Route::get('galleries/search', 'GalleryController@search')->name('galleries.search');
    Route::resource('galleries', GalleryController::class);

    // Story routes
    Route::get('stories/search', 'StoryController@search')->name('stories.search');
    Route::resource('stories', StoryController::class);

    // Slider routes
    Route::resource('sliders', SliderController::class);

    // Partner routes
    Route::resource('partners', PartnerController::class);

    // Video routes
    Route::get('videos/search', 'VideoController@search')->name('videos.search');
    Route::resource('videos', VideoController::class);

    // Statistics routes
    Route::resource('statistics', StatisticsController::class);

    // Page routes
    Route::get('pages/search', 'PageController@search')->name('pages.search');
    Route::resource('pages', PageController::class);

    // Mail routes
    Route::get('mails/search', 'MailController@search')->name('mails.search');
    Route::resource('mails', MailController::class);

    // User routes
    Route::resource('users', UserController::class);

    // Role routes
    Route::resource('roles', RoleController::class);

    // Permission routes
    Route::get('permissions/search', 'PermissionController@search')->name('permissions.search');
    Route::resource('permissions', PermissionController::class)->except('show');

    // Setting routes
    Route::get('settings', 'SettingController@index')->name('settings.index');
    Route::post('settings', 'SettingController@store')->name('settings.store');
    Route::put('settings', 'SettingController@updateAll')->name('settings.updateAll');

    // Unisharp
    Route::group(['prefix' => 'laravel-filemanager'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
});