<?php

namespace App\Http\Controllers;

use App\Services\UploadService;
use Illuminate\Http\Request;
use App\Http\Requests\FileUploadRequest;

class UploadController extends Controller
{
    public function __construct(UploadService $service)
    {
        $this->service = $service;
    }

    public function upload(FileUploadRequest $request)
    {
        $execution = $this->service->handleUpload($request);
        return response()->json($execution, $execution['code']);
    }
    
}
