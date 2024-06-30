<?php

namespace App\Services;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Storage;

class UploadService
{
    protected $helper;

    public function __construct(Helper $Helper)
    {
        $this->helper = $Helper;
    }

    public function handleUpload(Request $request)
    {
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();
        if ($save->isFinished()) {
            $saveFileResponse = $this->saveFileResponse($save->getFile());
            $response = $this->helper->apiResponse('success', 200, 'Upload completed.', $saveFileResponse);
            return $response;
        }

        $handler = $save->handler();
        $percentageDone = $handler->getPercentageDone();
        $response = $this->helper->apiResponse('success', 200, 'Upload in progress.', ['progress' => $percentageDone]);
        return $response;
    }

    protected function saveFileResponse(UploadedFile $file)
    {
        $originalFileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mime = str_replace('/', '-', $file->getMimeType());

        $dateFolder = date("Y-m-W");
        $filePath = "upload/{$extension}/{$dateFolder}/";
        $finalPath = storage_path("app/public/" . $filePath);

        $file->move($finalPath, $originalFileName);
        $storedFilePath = "{$filePath}{$originalFileName}";

        $exists = Storage::disk('public')->exists($storedFilePath);
        $downloadLink = Storage::disk('public')->url($storedFilePath);
        $base_url = url('/');
        $downloadLink = str_replace('http://localhost', $base_url, $downloadLink);

        $response = [
            'path' => $storedFilePath,
            'name' => $originalFileName,
            'mime_type' => $mime,
            'extension' => $extension,
            'is_file_exist' => $exists,
            'download_link' => $downloadLink,
        ];

        return $response;
    }

    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace("." . $extension, "", $file->getClientOriginalName());
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }

    public function listFiles()
    {
        $directory = 'public/upload/mp4/2024-06-26';
        $files = Storage::disk('local')->files($directory);
        return response()->json([
            'files' => $files,
        ]);
    }
}
