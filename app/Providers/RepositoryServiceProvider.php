<?php

namespace App\Providers;

use App\Interfaces\AppRepositoryInterface;
use App\Repositories\AppRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AppRepositoryInterface::class, AppRepository::class);
    }
}
