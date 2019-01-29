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
Route::name('login')->get('/login', 'Auth\LoginController@showLoginForm');
Route::name('login')->post('/login', 'Auth\LoginController@login');
Route::name('logout')->get('/logout', 'Auth\LoginController@logout');

// Testing
if (config('app.testing_menu')) {
    require(__DIR__ . '/testing.php');
}

/******************
 * Common
 ******************/
Route::name('app.')->group(function () {
    Route::name('logs.index')->get('/logs', 'LogsController@index');
    Route::name('settings.edit')->get('/settings', 'SettingsController@edit');
    Route::name('settings.update')->post('/settings', 'SettingsController@update');
    Route::name('settings.update-image')->post('/settings/image/{image}', 'SettingsController@updateImage');
    Route::name('faq')->get('/faq', 'AppController@faq');
});

/******************
 * Users
 ******************/
Route::name('users.')->prefix('/users')->group(function() {
    Route::name('index')->get('/', 'UsersController@index');
    Route::name('create')->get('/create', 'UsersController@create');
    Route::name('store')->post('/', 'UsersController@store');

    // Individual user
    Route::prefix('/{user}')->group(function() {
        Route::name('show')->get('/', 'UsersController@show');
        Route::name('edit')->get('/edit', 'UsersController@edit');
        Route::name('update')->patch('/', 'UsersController@update');
        Route::name('confirm-destroy')->get('/confirm-destroy', 'UsersController@confirmDestroy');
        Route::name('destroy')->delete('/', 'UsersController@destroy');
    });

});

/******************
 * Tags
 ******************/
Route::name('tags.')->prefix('/tags')->group(function () {
    Route::name('index')->get('/', 'TagsController@index');
    Route::name('api.search')->get('/api/search', 'TagsController@search');
    Route::name('api.update')->post('/api', 'TagsController@update');

});

/******************
 * Schools
 ******************/
Route::name('schools.')->prefix('/schools')->group(function () {
    Route::name('index')->get('/', 'SchoolsController@index');
    Route::name('edit')->get('/edit', 'SchoolsController@edit');
    Route::name('update')->post('/edit', 'SchoolsController@update');

    // Individual school
    Route::prefix('/{school}')->group(function () {
        Route::name('show')->get('/', 'SchoolsController@show');
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
        Route::name('update-image')->post('/image', 'ProfilesController@updateImage');
        Route::name('update-banner')->post('/banner', 'ProfilesController@updateBanner');
        Route::name('orcid')->get('/orcid', 'ProfilesController@orcid');
    });

});
