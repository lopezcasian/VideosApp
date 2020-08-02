<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controller\VideoController;
use App\Interfaces\OrderVideosInterface;

use App\Classes\OrderVideosLatest;
use App\Classes\OrderVideosOldest;
use App\Classes\OrderVideosTitleAsc;
use App\Classes\OrderVideosTitleDesc;

use App\Video;

class VideosOrderServiceProvider extends ServiceProvider
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
        $this->app->bind( OrderVideosInterface::class, function ($app) {
            $order = $this->app->request->get("order");

            if( $order == "new" ) {
                return new OrderVideosLatest( new Video );
            }

            if( $order == "old" ) {
                return new OrderVideosOldest( new Video );
            }

            if( $order == "atoz" ) {
                return new OrderVideosTitleAsc( new Video );
            }

            return new OrderVideosLatest( new Video );
        });
    }
}
