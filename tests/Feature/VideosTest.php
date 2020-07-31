<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Video;
use App\User;
use App\Comment;
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
        $video = factory( Video::class )->make([
                    'image' =>'', 
                    'video_path' => ''
                ]);

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
        $video = factory( Video::class )->make([
                    'image' =>'', 
                    'video_path' => ''
                ]);

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
        $video = factory( Video::class )->create();

        $response = $this->get( '/videos/file/' . $video->video_path );
        
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
        $video = factory( Video::class )->create();
        
        $response = $this->get( '/videos/miniature/' . $video->image );
        
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

    /**
     * Get edit video view
     *
     * @return void
     */
    public function testGetEditVideoView()
    {
        $user = factory( User::class )->create();
        $video = factory( Video::class )->create([
                    "user_id" => $user->id
                ]);

        $response = $this->actingAs( $user )
                            ->get( "/videos/$video->id/edit" );

        $response->assertStatus( 200 );
        $response->assertSee( $video->name );
        $response->assertSee( $video->description );
    }

    /**
     * Get edit video view of an unexistend video
     *
     * @return void
     */
    public function testGetEditVideoViewOfUnexistentVideo()
    {
        $user = factory( User::class )->create();
        $response = $this->actingAs( $user )
                            ->get( "/videos/asdfasdfsdfasdf234/edit" );

        $response->assertStatus( 404 );
    }

    /**
     * Get edit video view of an unauthorized user
     *
     * @return void
     */
    public function testGetEditVideoViewOfAnUnauthorizedUser()
    {
        $user1 = factory( User::class )->create();
        $video1 = factory( Video::class )->create([
                    "user_id" => $user1->id
                ]);

        $user2 = factory( User::class )->create();
        $video2 = factory( Video::class )->create([
                    "user_id" => $user2->id
                ]);

        $response1 = $this->actingAs( $user1 )
                            ->get( "/videos/$video2->id/edit" );

        $response2 = $this->actingAs( $user2 )
                            ->get( "/videos/$video1->id/edit" );

        $response1->assertStatus( 302 );
        $response2->assertStatus( 302 );
    }

    /**
     * Edit video
     * 
     * @return void
     */
    public function testEditVideo()
    {
        $user = factory( User::class )->create();
        $video = factory( Video::class )->create([
                    "user_id" => $user->id
                ]);

        $other_video = factory( Video::class )->make(["video_path" => "", "image" => ""]);
        
        $file_video = UploadedFile::fake()->create( 'update_video.mp4', 10000 );
        $file_image = UploadedFile::fake()->image( 'update_img.jpg' );

        $response = $this->actingAs( $user )
                            ->put( "/videos/$video->id",[
                                "title" => $other_video->title,
                                "description" => $other_video->description,
                                "video" => $file_video,
                                "image" => $file_image
                            ] );

        $response->assertStatus(302);

        $this->assertDatabaseHas( "videos", [
                    "id" => $video->id,
                    "title" => $other_video->title,
                    "description" => $other_video->description,
                    "user_id" => $video->user_id
                ]);

        $this->assertDatabaseMissing( "videos", [
                "title" => $video->title,
                "description" => $video->description,
                "video_path" => $video->video_path,
                "image" => $video->image
            ]);
    }


    /**
     * Delete a video and relations
     *
     * @return void
     */
    public function testDeleteVideo()
    {
        $user = factory( User::class )->create();
        $video = factory( Video::class )->create([
                    "user_id" => $user->id
                ]);
        $comments = factory( Comment::class, 3 )->create([
                "video_id" => $video->id
            ]);

        $response = $this->actingAs( $user )
                            ->delete( "/videos/$video->id");

        $response->assertStatus( 302 );

        $this->assertDatabaseMissing("comments", [
                "video_id" => $video->id
            ]);

        $this->assertDatabaseMissing("videos", [
                "id" => $video->id
            ]);

        # TODO: Assert storage
    }


}
