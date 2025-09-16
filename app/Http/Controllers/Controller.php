<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function initiatePdfDownload(string $token)
    {
        return view('initiate-download', compact('token'));
    }

    public function downloadPdfFile(string $path, string $name)
    {

        abort_unless(is_string($path), 403);
        abort_unless(Storage::exists($path), 404);

        $absolute = Storage::path($path);

        return response()->download($absolute, $name)->deleteFileAfterSend(true);
    }
}
