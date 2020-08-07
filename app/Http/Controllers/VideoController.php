<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Video;
use App\Comment;
use App\Http\Requests\StoreVideo;
use App\Http\Requests\UpdateVideo;
use App\Interfaces\VideoStorageInterface;
use App\Interfaces\ImageStorageInterface;
use App\Interfaces\OrderVideosInterface;

class VideoController extends Controller
{

    public function __construct( VideoStorageInterface $video_storage, 
                                 ImageStorageInterface $image_storage, 
                                 Video $video )
    {
        $this->video_storage = $video_storage;
        $this->image_storage = $image_storage;
        $this->video = $video;
    }

    /**
     * Store video
     *
     * @param \App\Http\Requests\StoreVideo $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store( StoreVideo $request )
    {
        
        $this->video->title       = $request->input('title');
        $this->video->description = $request->input('description');
        $this->video->video_path  = $this->video_storage->save( $request->file( 'video' ) );
        $this->video->image       = $this->image_storage->save( $request->file( 'image' ) );
        $this->video->user_id     = \Auth::id();

        $this->video->save();

        return redirect()->route( 'home' )->with( array(
            'message' => 'The video has been uploaded successfully.'
          ));
    }

    public function getImage( $filename )
    {
        $file = $this->image_storage->get( $filename ); 
        return new Response( $file, 200 );
    }

    /**
     * Show videos details
     * 
     * @param Video $video
     * @return Response
     */
    public function show( Video $video )
    {
        return view( 'video.detail', compact("video") );
    }

    /**
     * Get video from storage
     *
     * @param string $filename
     */
    public function getVideo( string $filename )
    {
        $file = $this->video_storage->get( $filename );
        return new Response( $file, 200 );
    }


    /**
     * Destroy video
     * 
     * @param Video $video
     * @return Redirect
     */
    public function destroy( Video $video )
    {
        $this->authorize( 'delete', $video );
        
        $video->comments()->delete();

        $this->image_storage->destroy( $video->image );
        $this->video_storage->destroy( $video->video_path );

        $video->delete();

        $message = array( 
          'message' => 'Video deleted successfully.' 
        );

        return redirect()->route('home')->with($message);

    }

    /**
     * Get video edit view
     * 
     * @param Video $video
     * @return Redirect
     */
    public function edit( Video $video )
    {
        $user = \Auth::user();
        
        if( $user->can('update', $video) ){
            return view( 'video.edit', compact( "video" ) );
        }

        return redirect()->route( 'home' );
    }


    /**
     * Edit video
     * 
     * @param Video $video
     * @param App\Http\Requests\UpdateVideo $request
     *
     * @return Redirect
     */
    public function update( Video $video, UpdateVideo $request )
    {
        $video->title       = $request->input('title');
        $video->description = $request->input('description');
        
        $imageOfRequest = $request->file('image');
        if( $imageOfRequest ){
            $oldImage = $video->image;
            $video->image = $this->image_storage->save( $imageOfRequest );

            $this->image_storage->destroy( $oldImage );
        }

        $videoOfRequest = $request->file('video');
        if( $videoOfRequest ){
            $oldVideo = $video->video_path;
            $video->video_path = $this->video_storage->save( $videoOfRequest );

            $this->video_storage->destroy( $oldVideo );
        }

        $video->update();

        return redirect()->route('home')->with(array(
                'message' => 'The video has been updated successfully.'
            ));
    }

    /**
     * Search videos
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Interfaces\OrderVideosInterface $orderVideos
     *
     * @return View
     */
    public function search( Request $request, OrderVideosInterface $orderVideos ){
        $stringToSearch = $request->get('search');

        if( empty( $stringToSearch ) ){
            return redirect()->route('home');
        }

        $orderScope = $orderVideos->getOrderScope();

        $videos = $orderScope->filterByTitle( $stringToSearch )
                    ->paginate(5);

        return view('video.search', array(
                'videos' => $videos,
                'search' => $stringToSearch
            ));
    }
}