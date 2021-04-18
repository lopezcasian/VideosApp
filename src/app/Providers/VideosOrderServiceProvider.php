<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\OrderVideosInterface;

use App\Classes\VideoOrdering\OrderByLatest;
use App\Classes\VideoOrdering\OrderByOldest;
use App\Classes\VideoOrdering\OrderByTitleAsc;
use App\Classes\VideoOrdering\OrderByTitleDesc;

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
     * @param string $attrs 
     * @return void
     */
    public function register()
    {
        $this->app->bind( OrderVideosInterface::class, function ( $app, $attrs ) {
            $order = $attrs['order'];
            if( $order == "new" ) {
                return new OrderByLatest();
            }

            if( $order == "old" ) {
                return new OrderByOldest();
            }

            if( $order == "atoz" ) {
                return new OrderByTitleAsc();
            }

            return new OrderByLatest();
        });
    }
}
