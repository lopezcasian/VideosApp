<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\Classes\VideoMiniatureStorage;

class VideoMiniatureStorageTest extends TestCase
{
    
    public function setUp(): void
    {
        parent::setUp();
        Storage::fake( 'images' );

        $this->storage = Storage::disk( 'videos' );
    }

    /**
     * Save image in the system
     *
     * @return void
     */
    public function testSaveMiniature()
    {
        $sut = new VideoMiniatureStorage( $this->storage );

        $file = UploadedFile::fake()->image( 'miniature_unit_test.jpg' );

        $result = $sut->save( $file );

        $this->storage->assertExists( $result );
    }

    # TODO: Get file from storage

    /**
     * Destroy miniature from storage
     *
     * @return void
     */
    public function testDeleteMiniature()
    {
        $file_name = "miniature_unit_test_destroy.jpg";
        $file = UploadedFile::fake()->image( $file_name );

        $this->storage->put( $file_name, $file );

        $sut = new VideoMiniatureStorage( $this->storage );

        $saved_file = $sut->save( $file );
        $sut->destroy( $saved_file );

        $this->storage->assertMissing( $saved_file );
    }
}
