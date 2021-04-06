<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\Classes\FileVideoStorage;

class FileVideoStorageTest extends TestCase
{
    private $storage = null;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake( 'videos' );

        $this->storage = Storage::disk( 'videos' );
    }

    /**
     * Save video to storage
     *
     * @return void
     */
    public function testSaveVideoToStorage()
    {
        $sut = new FileVideoStorage( $this->storage );

        $file = UploadedFile::fake()->create( 'video_unit_test.mp4', 10000 );

        $result = $sut->save( $file );

        $this->storage->assertExists( $result );
    }


    # TODO: Get file from storage

    /**
     * Destroy video from storage
     *
     * @return void
     */
    public function testDeleteVideo()
    {
        $file_name = "video_unit_test_destroy.mp4";
        $file = UploadedFile::fake()->create( $file_name, 10000 );

        $sut = new FileVideoStorage( $this->storage );

        $saved_file = $sut->save( $file );
        $sut->destroy( $saved_file );

        $this->storage->assertMissing( $saved_file );
    }
}
