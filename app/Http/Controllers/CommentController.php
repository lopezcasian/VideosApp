<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreComment;
use App\Comment;

class CommentController extends Controller
{
    public function __construct( Comment $comment )
    {
        $this->comment = $comment;
    }


    /**
     * Store comment
     *
     * @param \App\Http\Requests\StoreComment $request
     * @param \Illuminate\Http\RedirectResponse
     */
    public function store( StoreComment $request )
    {
    	$this->comment->video_id = $request->input('video_id');
    	$this->comment->body     = $request->input('body');
        $this->comment->user_id  = \Auth::id();

    	$this->comment->save();

    	return redirect()->route('videos.show', ['video_id' => $this->comment->video_id])->with(array(
    				'message' => 'Comment added successfully.'
    			));
    }

    /**
     * Delete comment
     *
     * @param \App\Comment $comment
     * @param \Illuminate\Http\RedirectResponse
     */
    public function destroy( Comment $comment ){
        $this->authorize('delete', $comment );

        $comment->delete();

        return redirect()->route('videos.show', ['video_id' => $comment->video_id])->with( array(
                    'message' => 'Comment deleted successfully.'
                ));
    }
}
