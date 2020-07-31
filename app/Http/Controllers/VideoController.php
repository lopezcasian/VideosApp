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
     * @param App\Http\Requests\StoreVideo $request
     * @return Response
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

    public function edit( Video $video )
    {
        $user = \Auth::user();
        
        if( $user->can('update', $video) ){
            return view( 'video.edit', compact( "video" ) );
        }

        return redirect()->route( 'home' );
    }

    public function update( Video $video, UpdateVideo $request )
    {
        $video->title       = $request->input('title');
        $video->description = $request->input('description');
        
        $image = $request->file('image');
        if( $image ){
            $old_image = $video->image;
            $video->image = $this->image_storage->save( $image );

            $this->image_storage->destroy( $old_image );
        }

        $video_file = $request->file('video');
        if( $video_file ){
            $old_video = $video->video_path;
            $video->video_path = $this->video_storage->save( $video_file );

            $this->video_storage->destroy( $old_image );
        }

        $video->update();

        return redirect()->route('home')->with(array(
                'message' => 'The video has been updated successfully.'
            ));
    }

    public function search($search = null, $filter = null){
        if(is_null($search)){
            $search = \Request::get('search');

            if (is_null($search)) {
                return redirect()->route('home');
            }

            return redirect()->route('videoSearch', array('search' => $search));
        }

        if(is_null($filter) && \Request::get('filter') && !is_null($search)){
            $filter = \Request::get('filter');

            return redirect()->route('videoSearch', array(
                    'search' => $search,
                    'filter' => $filter)
                );
        }

        $column = 'id';
        $order = 'desc';

        if(!is_null($filter)){
            if($filter == 'new'){
                $column = 'id';
                $order = 'desc';
            }

            if($filter == 'old'){
                $column = 'id';
                $order = 'asc';    
            }

            if ($filter == 'atoz') {
                $column = 'title';
                $order = 'asc'; 
            }
            
        }

        $videos = Video::where('title', 'LIKE', '%' . $search . '%')
                    ->orderBy($column, $order)
                    ->paginate(5);

        return view('video.search', array(
                'videos' => $videos,
                'search' => $search
            ));
    }
}