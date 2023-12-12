<?php

namespace App\Providers;

use App\Repositories\Contracts\PublicationsRepositoryContract;
use App\Repositories\OrcidPublicationsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PublicationsRepositoryContract::class, OrcidPublicationsRepository::class);
    }
}
