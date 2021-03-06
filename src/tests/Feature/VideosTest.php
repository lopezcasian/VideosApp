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

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('videos');
        Storage::fake('images');
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
        $file_storage = new FileVideoStorage( Storage::disk('videos') );
        
        $video = $file_storage->save( UploadedFile::fake()->create( 'video_unit_test.mp4', 10000 ) );
        //dd( $video );
        $response = $this->get( '/videos/file/' . $video );
        
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
        $file_storage = new VideoMiniatureStorage( Storage::disk('images') );
        
        $image = $file_storage->save( UploadedFile::fake()->image( 'miniature_unit_test.jpg' ) );
        
        $response = $this->get( '/videos/miniature/' . $image );
        
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
     * Search videos
     *
     * @return void
     */
    public function testSearchVideos()
    {
        $videos = factory( Video::class, 15 )->create();

        $response = $this->post("/videos/search", [
                "search" => $videos[0]->title,
            ]);
        
        $response->assertSee( $videos[0]->title );
        $response->assertStatus( 200 );
    }

    /**
     * Order videos
     * 
     * @return void 
     */
    public function testOrderVideos(){
        $videos = factory( Video::class, 10 )->create([
            "title" => "Esteban"
        ]);

        $response = $this->post("/videos/search", [
            "search" => $videos[0]->title,
            "order" => "old"
        ]);
        
        
        $response->assertStatus( 200 );
        $response->assertViewHas( 'videos' );

        $videos = Video::where( 'title', 'LIKE', '%' . $videos[0]->title . '%' )->oldest()->paginate(5);
        $content = $response->getOriginalContent()->getData()['videos'];
        
        $this->assertEquals( $videos[0]->created_at, $content[0]->created_at );
    }

    /**
     * Test video soft delete
     *
     * @return void
     */
    public function testVideoSoftDelete()
    {
        $user = factory( User::class )->create();
        $video = factory( Video::class )->create( [ "user_id" => $user->id ] );

        $this->actingAs( $user )->delete( "/videos/$video->id" );

        $this->assertSoftDeleted( "videos", [
                "id" => $video->id,
                "title" => $video->title
            ]);

        #TODO: Assert storage
    }
}
