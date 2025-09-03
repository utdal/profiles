<?php

use App\Http\Controllers\ProfileStudentsController;
use App\Http\Controllers\StudentsController;

/******************
 * Routes for research student applications feature
 ******************/

Route::name('students.')->prefix('/students')->group(function () {

    Route::name('index')->get('/list', [StudentsController::class, 'index']);
    Route::name('about')->get('/about', [StudentsController::class, 'about']);
    Route::name('create')->get('/create', [StudentsController::class, 'create']);

    Route::prefix('/{student}')->group(function () {
        Route::name('show')->get('/', [StudentsController::class, 'show']);
        Route::name('edit')->get('/edit', [StudentsController::class, 'edit']);
        Route::name('update')->post('/update', [StudentsController::class, 'update']);
        Route::name('status')->patch('/status', [StudentsController::class, 'setStatus']);
    });

});

Route::name('profiles.')->prefix('/')->group(function () {

    Route::prefix('/{profile}')->group(function () {
        Route::name('students')->get('/students', [ProfileStudentsController::class, 'show']);
    
        Route::name('downloadPdf')
            ->get('/students/downloadPdf', [ProfileStudentsController::class, 'downloadPdf'])
            ->middleware('signed');

            Route::name('initiateDownload')
            ->get('/students/initiateDownload', [ProfileStudentsController::class, 'initiateDownload'])
            ->middleware('signed');
    });

});