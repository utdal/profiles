<?php 

use App\Http\Controllers\Testing\TestingController;

/******************
 * Routes for
 * Testing
 ******************/

Route::name('testing.')->prefix('/testing')->group(function () {

    Route::name('roles.')->prefix('/roles')->group(function () {

        Route::name('add')->get('{name}/add', [TestingController::class, 'attachRole']);
        Route::name('remove')->get('{name}/remove', [TestingController::class, 'detachRole']);

    });

    Route::name('login_as.')->prefix('/login_as')->group(function () {

        Route::name('select')->get('/', [TestingController::class, 'showLoginAsList']);
        Route::name('login')->get('/{id}', [TestingController::class, 'loginAs']);

    });

    Route::name('exception')->get('/throw-exception', [TestingController::class, 'throwException']);

});
