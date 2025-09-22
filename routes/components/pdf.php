<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ProfilesController;

// Profile PDF exports
Route::name('profiles.export.pdf')->get('/{profile}/pdf', [ProfilesController::class, 'pdfExport']);

// Student Apps Export
Route::name('pdf.')->prefix('/')->group(function () {

    Route::prefix('/{user}')->group(function () {
    
        Route::name('requestDownload')
            ->get('/requestDownload/{ability}/{model}/{token}', [AppController::class, 'requestDownload'])
            ->middleware('signed');

        Route::name('download')
            ->get('/download/{ability}/{model}', [AppController::class, 'download'])
            ->middleware('signed');

    });

});