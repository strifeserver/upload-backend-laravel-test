<?php

namespace Tests\Unit;

use App\Helpers\Helper;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class UploadServiceTest extends TestCase
{
    /**
     * Test the handleUpload method of UploadService.
     */
    public function testHandleUploadSuccess()
    {
        // SETTINGS
        $helperMock = $this->mockHelper();
        $filename = 'testfile';
        $extension = 'txt';
        $dateFolder = date("Y-m-W");
        $filePath = "upload/{$extension}/{$dateFolder}/";
        $fileSize = 1024;
        $createFile = "{$filename}.{$extension}";
        // SETTINGS

        $file = UploadedFile::fake()->create($createFile, $fileSize);
        $request = $this->mockRequestWithFile($file);

        $service = new UploadService($helperMock);
        $response = $service->handleUpload($request);

        //CHECKING
        $this->assertIsArray($response, 'Response should be an array.');
        $this->assertEquals('success', $response['status'], 'Expected status to be "success".');
        $this->assertEquals(200, $response['code'], 'Expected code to be 200.');
        $this->assertEquals(true, $response['data']['is_file_exist'], 'Expected to be true');
        //CHECKING

    }

    /**
     * Mock a Helper instance.
     *
     * @return \Mockery\MockInterface
     */
    protected function mockHelper()
    {
        $helperMock = Mockery::mock(Helper::class);
        $helperMock->shouldReceive('apiResponse')->andReturnUsing(function ($status, $code, $message, $data = []) {
            return compact('status', 'code', 'message', 'data');
        });

        return $helperMock;
    }

    /**
     * Mock a request with a file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return \Illuminate\Http\Request
     */
    protected function mockRequestWithFile(UploadedFile $file)
    {
        return Request::create('/upload', 'POST', [], [], ['file' => $file]);
    }

    /**
     * Perform any necessary cleanup after the test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
