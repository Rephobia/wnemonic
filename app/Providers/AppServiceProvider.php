<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("Repository", function ($app) {
            return new Repository;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
