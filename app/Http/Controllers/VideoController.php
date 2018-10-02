<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

use App\Video;
use App\Comment;

class VideoController extends Controller
{
    public function createVideo(){
    	return view('video.createVideo');
    }

    public function saveVideo(Request $request){
    	// Form validation
    	$validatedData = $this->validate($request, array(
    			'title' => 'required|min:5',
    			'description' => 'required',
    			'video' => 'mimes:mp4'
    		));

    	$video = new Video();
    	$user = \Auth::user();

    	$video->user_id = $user->id;
    	$video->title = $request->input('title');
    	$video->description = $request->input('description');

    	// Upload miniature

    	$image = $request->file('image');

    	if( $image ){
    		$image_path = time() . $image->getClientOriginalName();
    		\Storage::disk('images')->put($image_path, \File::get($image));
    		$video->image = $image_path;
    	}

    	// Upload video

    	$video_file = $request->file('video');

    	if( $video_file ){
    		$video_path = time() . $video_file->getClientOriginalName();
    		\Storage::disk('videos')->put($video_path, \File::get($video_file));
    		$video->video_path = $video_path;
    	}

    	$video->save();

    	return redirect()->route('home')->with(array(
    			'message' => 'The video has been uploaded successfully.'
    		));
    }
}
