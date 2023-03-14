<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/******************
 * Auth
 ******************/

use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\UsersController;

Route::name('login.show')->get('/login', 'Auth\LoginController@showLoginForm');
Route::name('login')->post('/login', 'Auth\LoginController@login');
Route::name('logout')->get('/logout', 'Auth\LoginController@logout');

/******************
 * Common
 ******************/
Route::name('app.')->group(function () {
    Route::name('logs.index')->get('/logs', 'LogsController@index');
    Route::name('settings.edit')->get('/settings', 'SettingsController@edit');
    Route::name('settings.update')->post('/settings', 'SettingsController@update');
    Route::name('settings.update-image')->post('/settings/image/{image}', 'SettingsController@updateImage')
        ->where('image', '(logo|favicon|student_info_image)');
    Route::name('faq')->get('/faq', 'AppController@faq');
});

/******************
 * Users
 ******************/
Route::name('users.')->prefix('/users')->group(function() {
    Route::name('index')->get('/', 'UsersController@index');
    Route::name('create')->get('/create', 'UsersController@create');
    Route::name('store')->post('/', 'UsersController@store');
    Route::name('delegations-index')->get('/delegations', 'UserDelegationsController@index');

    // Individual user
    Route::prefix('/{user}')->group(function() {
        Route::name('show')->get('/', 'UsersController@show');
        Route::name('edit')->get('/edit', 'UsersController@edit');
        Route::name('update')->patch('/', 'UsersController@update');
        Route::name('bookmarks.show')->get('/bookmarks', 'UsersController@showBookmarks');
        Route::name('delegations.show')->get('/delegations', 'UserDelegationsController@show');
        Route::name('confirm-delete')->get('confirm-delete', [UsersController::class, 'confirmDelete']);
        Route::name('delete')->delete('delete', [UsersController::class, 'destroy']);
    });

});

/******************
 * Tags
 ******************/
Route::name('tags.')->prefix('/tags')->group(function () {
    Route::name('index')->get('/', 'TagsController@index');
    Route::name('table')->get('/table', 'TagsController@table');
    Route::name('create')->get('/create', 'TagsController@create');
    Route::name('store')->post('/store', 'TagsController@store');
    Route::name('api.search')->get('/api/search', 'TagsController@search');
    Route::name('api.update')->post('/api', 'TagsController@update');

});

/******************
 * Schools
 ******************/
Route::name('schools.')->prefix('/schools')->group(function () {
    Route::name('index')->get('/', 'SchoolsController@index');
    Route::name('create')->get('/create', 'SchoolsController@create');
    Route::name('store')->post('/edit', 'SchoolsController@store');

    // Individual school
    Route::prefix('/{school}')->group(function () {
        Route::name('show')->get('/', 'SchoolsController@show');
        Route::name('edit')->get('/edit', 'SchoolsController@edit');
        Route::name('update')->patch('/edit', 'SchoolsController@update');
    });

});

/******************
 * Profiles
 ******************/
Route::name('profiles.')->prefix('/')->group(function() {
    Route::name('home')->get('/', 'ProfilesController@home');
    Route::name('index')->get('/browse', 'ProfilesController@index');
    Route::name('table')->get('/profiles', 'ProfilesController@table');
    Route::name('create')->get('/create/{user}', 'ProfilesController@create');

    //catch ID-based URLs and redirect to name-based
    Route::name('by_id')->get('/{id}', 'ProfilesController@redirectById')->where('id', '[0-9]+');

    // Individual Profile
    Route::prefix('/{profile}')->group(function() {
        Route::name('show')->get('/', 'ProfilesController@show');
        Route::name('edit')->get('/edit/{section}', 'ProfilesController@edit');
        Route::name('update')->post('/update/{section}', 'ProfilesController@update');
        Route::name('confirm-delete')->get('confirm-delete/{create_attempt?}', [ProfilesController::class, 'confirmDelete'])->withTrashed();
        Route::name('archive')->delete('archive', [ProfilesController::class, 'archive']);
        Route::name('restore')->post('restore', [ProfilesController::class, 'restore'])->withTrashed();
        Route::name('update-image')->post('/image', 'ProfilesController@updateImage');
        Route::name('update-banner')->post('/banner', 'ProfilesController@updateBanner');
        Route::name('orcid')->get('/orcid', 'ProfilesController@orcid');
    });

});
