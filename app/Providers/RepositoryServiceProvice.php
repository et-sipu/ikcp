<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Composer\Autoload\ClassMapGenerator;

class RepositoryServiceProvice extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }

    /**
     * Register services.
     */
    public function register()
    {
        foreach (array_keys(ClassMapGenerator::createMap(app_path('/Repositories/Contracts'))) as $contract) {
            $this->app->bind($contract, 'App\Repositories\Eloquent'.last(explode('\\', $contract)));
        }
    }
}
