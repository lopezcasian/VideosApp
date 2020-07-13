<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

use App\Video;
use App\Comment;
use App\User;

class UserController extends Controller
{
    /**
     * Show user data.
     * 
     * @params App\User $user
     * @return Response
     */
    public function show( User $user ){
        $videos = $user->videos()->paginate( 5 );

        return view( 'user.channel', compact("user", "videos") );
    }


    /**
    * TODO: Create infraestructure of profile images.
    */
    public function getProfileImage( $image = null ){
        $storage = Storage::disk( 'profile' );

        if( !is_null($image) ){
            $file = $storage->get( $image );
        } else {
            $file = $storage->get( 'no_user.png' );
        }
        
        return new Response( $file, 200 );
    }
}
