<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Video;
use App\Comment;

class CommentTest extends TestCase
{
    use DatabaseTransactions;
    
    private $table_name = "comments";

    private $video_file_disk_name = "videos";
    private $video_miniature_disk_name = "images";

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake( $this->video_file_disk_name );
        Storage::fake( $this->video_miniature_disk_name );
    }

    /**
     * Store a comment.
     *
     * @return void
     */
    public function testStoreAComment()
    {
        $user = factory( User::class )->create();
        $video = factory( Video::class )->create();
        $comment = factory( Comment::class )->make();

        $response = $this->actingAs( $user )->post( "/comments", [
                "video_id" => $video->id,
                "body" => $comment->body
            ]);
        
        $response->assertStatus( 302 );
        $response->assertRedirect( "/videos/" . $video->id );

        $this->assertDatabaseHas( $this->table_name, [
                "body" => $comment->body,
                "video_id" => $video->id,
                "user_id" => $user->id
            ] );
    }

    #TODO: Store comment to an un-existent video
    #TODO: Store comment with invalid data.

    /**
     * Delete a comment
     *
     * @return void
     */
    public function testDeleteAComment()
    {
        $user = factory( User::class )->create();
        $comment = factory( Comment::class )->create([
                "user_id" => $user->id
            ]);

        $response = $this->actingAs( $user )
                            ->delete( "/comments/" . $comment->id );

        $response->assertStatus( 302 );
        $response->assertRedirect( "/videos/" . $comment->video_id );

        $this->assertDatabaseMissing( "comments", [
                "id" => $comment->id,
                "body" => $comment->body,
                "video_id" => $comment->video_id,
                "user_id" => $user->id
            ] );
    }

    /**
     * Test comment soft delete
     *
     * @return void
     */
    public function testCommentSoftDelete()
    {
        $user = factory( User::class )->create();
        $video = factory( Video::class )->create();
        $comment = factory( Comment::class )->create([
                    "video_id" => $video->id,
                    "user_id" => $user->id
                ]);

        $this->actingAs( $user )->delete( "/comments/$comment->id" );

        $this->assertSoftDeleted( "comments", [
                "id" => $comment->id,
            ]);
    }
}
