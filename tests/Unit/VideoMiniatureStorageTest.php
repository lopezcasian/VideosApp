<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\Classes\VideoMiniatureStorage;

class VideoMiniatureStorageTest extends TestCase
{
    
    public function setUp()
    {
        parent::setUp();
        Storage::fake( 'images' );
    }

    /**
     * Save image in the system
     *
     * @return void
     */
    public function testSaveMiniature()
    {
        $storage = Storage::disk( 'images' );

        $sut = new VideoMiniatureStorage( $storage );

        $file = UploadedFile::fake()->image( 'miniature_unit_test.jpg' );

        $result = $sut->save( $file );

        $storage->assertExists( $result );
    }
}
