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
