<?php

use App\Http\Controllers\ProfileStudentsController;
use App\Http\Controllers\ProfilesController;

// Profile PDF exports
Route::name('profiles.export.pdf')->get('/{profile}/pdf', [ProfilesController::class, 'pdfExport']);

// Student Apps Export
Route::name('students.')->prefix('/')->group(function () {

    Route::prefix('/{user}')->group(function () {
    
        Route::name('requestDownload')
            ->get('/requestDownload/{token}', [ProfileStudentsController::class, 'requestDownload'])
            ->middleware('signed');

        Route::name('downloadPdf')
            ->get('/downloadPdf', [ProfileStudentsController::class, 'downloadPdf'])
            ->middleware('signed');

    });

});