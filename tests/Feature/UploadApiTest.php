<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadApiTest extends TestCase
{
    public function testUploadApi()
    {
        $file = UploadedFile::fake()->create('testvideo.mp4', 10240); 
        $response = $this->postJson('/api/upload', ['file' => $file]);
        //CHECKING
        $response->assertStatus(200);
        $this->assertEquals('success', $response['status'], 'Expected status to be "success".');
        $this->assertEquals(true, $response['result']['is_file_exist'], 'Expected to be true');
        //CHECKING

    }

}
