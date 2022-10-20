<?php

namespace App\Providers;

use App\Kahoodie\Manager;
use Illuminate\Support\ServiceProvider;

class KahoodieServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Manager::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
