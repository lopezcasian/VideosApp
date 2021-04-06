<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

use App\Classes\VideoMiniatureStorage;

class ImageStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( 'App\Classes\VideoMiniatureStorage', function( $app ){
            $storage = Storage::disk( 'images' );
            return new VideoMiniatureStorage( $storage );
        } );

        $this->app->when( VideoController::class )
            ->needs( 'App\Interfaces\ImageStorageInterface' )
            ->give( function ( $app ) {
                return $app->make( 'App\Classes\VideoMiniatureStorage' );
            });
    }
}