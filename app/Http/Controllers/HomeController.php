<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Video;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
        $this->middleware('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = $this->video->orderBy('id','desc')->paginate(5);
        return view('home', compact( "videos" ));
    }
}
