<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Video;
use App\User;
use App\Classes\FileVideoStorage;
use App\Classes\VideoMiniatureStorage;

class VideosTest extends TestCase
{
    use DatabaseTransactions;

    private $table = "videos";
    private $video_file_disk_name = "videos";
    private $video_miniature_disk_name = "images";

    public function setUp()
    {
        parent::setUp();
        Storage::fake( $this->video_file_disk_name );
        Storage::fake( $this->video_miniature_disk_name );
    }

    /**
     * Store video test
     *
     * @return void
     */
    public function testStoreVideoWithValidData()
    {
        $user = factory( User::class )->create();
        $video = factory( Video::class )->make();

        $file_video = UploadedFile::fake()->create( 'video_test.mp4', 10000 );
        $file_image = UploadedFile::fake()->image( 'miniature_test.jpg' );

        $response = $this->actingAs( $user )
                            ->post( '/videos', [
                                "title" => $video->title,
                                "description" => $video->description,
                                "video" => $file_video,
                                "image" => $file_image
                            ] );

        $response->assertStatus( 302 );

        $this->assertDatabaseHas( $this->table, [
            "title" => $video->title,
            "description" => $video->description,
            "user_id" => $user->id
        ] );
    }


    /**
     * Store video test with invalid data
     *
     * @return void
     */
    public function testStoreVideoWithInvalidData()
    {
        $user = factory( User::class )->create();
        $video = factory( Video::class )->make();

        $file_video = UploadedFile::fake()->create( 'video_test.avi', 10000 );

        $response = $this->actingAs( $user )
                            ->post( '/videos', [
                                "title" => $video->title,
                                "description" => $video->description,
                                "video" => $file_video,
                                "image" => null
                            ] );

        $response->assertStatus( 302 );

        $response->assertSessionHasErrors(["video", "image"]);

        $this->assertDatabaseMissing( $this->table, [
            "title" => $video->title,
            "description" => $video->description,
            "user_id" => $user->id
        ] );
    }

    /**
     * Get video test
     *
     * @return void
     */
    public function testGetVideo()
    {
        $file = UploadedFile::fake()->create( "video_testing.mp4", 10000 );

        $storage = new FileVideoStorage( Storage::disk( $this->video_file_disk_name ) );
        $video_path = $storage->save( $file );

        $response = $this->get( '/videos/file/' . $video_path );
        
        $response->assertStatus(200);
    }

    /**
     * Get non-existent video test
     *
     * @return void
     */
    public function testGetNonexistentVideo()
    {
        $response = $this->get( '/videos/file/asdfasdf4564asdfweewfar6354543454444fdf.mp4');
        
        $response->assertStatus(404);
    }

    /**
     * Get video miniature test
     *
     * @return void
     */
    public function testGetVideoMiniature()
    {
        $file = UploadedFile::fake()->image( "image_testing.jpg" );

        $storage = new VideoMiniatureStorage( Storage::disk( $this->video_miniature_disk_name ) );
        $miniature_path = $storage->save( $file );
        
        $response = $this->get( '/videos/miniature/' . $miniature_path );
        
        $response->assertStatus(200);
    }

    /**
     * Get non-existent video test
     *
     * @return void
     */
    public function testGetNonexistentVideoMiniature()
    {
        $response = $this->get( '/videos/miniature/asdfasdf4564asdfweewfar6354543454444fdf.jpg');
        
        $response->assertStatus(404);
    }
}
