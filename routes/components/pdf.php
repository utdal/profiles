<?php

use App\Http\Controllers\ProfilesController;

// Profile PDF exports
Route::name('profiles.export.pdf')->get('/{profile}/pdf', [ProfilesController::class, 'pdfExport']);