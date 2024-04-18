<?php

namespace App\Providers;

use App\Profile;
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
        $this->app->bind(OrcidPublicationsRepository::class, function($app) {
            return new OrcidPublicationsRepository($app[Profile::class]);
        });
        $this->app->bind(PublicationsRepositoryContract::class, OrcidPublicationsRepository::class);
    }
}
