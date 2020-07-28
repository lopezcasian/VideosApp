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

    public function setUp()
    {
        parent::setUp();
        Storage::fake( 'videos' );
    }

    /**
     * Save video in the system
     *
     * @return void
     */
    public function testSaveVideo()
    {
        $storage = Storage::disk( 'videos_testing' );

        $sut = new FileVideoStorage( $storage );

        $file = UploadedFile::fake()->create( 'video_unit_test.mp4', 10000 );

        $result = $sut->save( $file );

        $storage->assertExists( $result );
    }

    /**
     * Get video from storage
     *
     * @return void
     */
    public function testGetVideo(){
        
    }
}
