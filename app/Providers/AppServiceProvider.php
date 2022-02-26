<?php

namespace App\Providers;

use App\Interfaces\AppServiceInterface;
use App\Services\AppService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        $this->app->bind(AppServiceInterface::class, AppService::class);
    }
}
