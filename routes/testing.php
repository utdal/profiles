<?php 

/******************
 * Routes for
 * Testing
 ******************/

Route::name('testing.')->prefix('/testing')->group(function () {

    Route::name('roles.')->prefix('/roles')->group(function () {

        Route::name('add')->get('{name}/add', 'Testing\TestingController@attachRole');
        Route::name('remove')->get('{name}/remove', 'Testing\TestingController@detachRole');

    });

    Route::name('login_as.')->prefix('/login_as')->group(function () {

        Route::name('select')->get('/', 'Testing\TestingController@showLoginAsList');
        Route::name('login')->get('/{id}', 'Testing\TestingController@loginAs');

    });

    Route::name('exception')->get('/throw-exception', 'Testing\TestingController@throwException');

});
