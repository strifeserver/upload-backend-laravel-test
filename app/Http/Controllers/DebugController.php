<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadService;
class DebugController extends Controller
{
    public function phpInfo()
    {
        return response()->make(phpinfo(), 200, ['Content-Type' => 'text/html']);
    }

    public function listFiles(){
        $UploadService = app(UploadService::class);
        $listFiles = $UploadService->listFiles();
        return $listFiles;
    }
}
