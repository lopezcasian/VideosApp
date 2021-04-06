<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\UuidObserver;

use App\Video;
use App\Comment;
use App\User;

class UuidObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Video::observe( app(UuidObserver::class) );
        Comment::observe( app(UuidObserver::class) );
        User::observe( app(UuidObserver::class) );
    }
}
