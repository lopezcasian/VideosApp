<?php

namespace App\Providers;

use App\Http\Controllers\VideoController;

class StorageableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(PhotoController::class)
            ->needs(Filesystem::class)
            ->give(function () {
                return Storage::disk('local');
            })
    }
}