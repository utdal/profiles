<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ProfilesController;

// Profile PDF exports
Route::name('profiles.export.pdf')->get('/{profile}/pdf', [ProfilesController::class, 'pdfExport']);

// Student Apps Export
Route::name('pdf.')->prefix('/')->group(function () {

    Route::prefix('/{user}')->group(function () {
    
        Route::name('requestDownload')
            ->get('/requestDownload/{token}', [AppController::class, 'requestDownload'])
            ->middleware('signed');

    });

});