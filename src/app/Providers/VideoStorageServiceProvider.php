<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

use App\Classes\FileVideoStorage;

class VideoStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( 'App\Classes\FileVideoStorage', function( $app ){
            $storage = Storage::disk( 'videos' );
            return new FileVideoStorage( $storage );
        } );

        $this->app->when( VideoController::class )
            ->needs( "App\Interfaces\VideoStorageInterface" )
            ->give( function ( $app ) {
                return $app->make( 'App\Classes\FileVideoStorage' );
            });
    }
}
