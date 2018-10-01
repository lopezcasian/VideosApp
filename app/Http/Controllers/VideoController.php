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
    }
}
