<?php

namespace App\Providers;

use App\Kahoodie\Kahoodie;
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
        $this->app->singleton(Kahoodie::class);
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
