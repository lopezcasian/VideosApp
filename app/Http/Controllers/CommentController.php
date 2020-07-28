<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    public function store(Request $request){
    	$validate = $this->validate($request, [
    			'body' => 'required'
    		]);

    	$comment = new Comment();
    	$user = \Auth::user();
    	$comment->user_id = $user->id;
    	$comment->video_id = $request->input('video_id');
    	$comment->body = $request->input('body');

    	$comment->save();

    	return redirect()->route('video.show', ['video_id' => $comment->video_id])->with(array(
    				'message' => 'Comment added successfully.'
    			));
    }

    public function destroy(Comment $comment){
        //$comment_id = $request->input('comment_id');
        $user = \Auth::user();

        if($user && ($comment->user_id == $user->id || $comment->video->user_id == $user->id)){
            $comment->delete();
        }

        return redirect()->route('video.show', ['video_id' => $comment->video_id])->with(array(
                    'message' => 'Comment deleted successfully.'
                ));
    }
}
