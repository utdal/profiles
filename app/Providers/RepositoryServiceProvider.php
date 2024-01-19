<?php

namespace App\Providers;

use App\Http\Controllers\ProfilesController;
use App\Profile;
use App\Repositories\Contracts\PublicationsRepositoryContract;
use App\Repositories\AcademicAnalyticsPublicationsRepository;
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
        // $this->app->when(PublicationsImportModal::class)
        // ->needs(PublicationsRepositoryContract::class)
        // ->give(function() {
        //     return new AcademicAnalyticsPublicationsRepository($profile);
        // });

        $this->app->when(ProfilesController::class)
                ->needs(PublicationsRepositoryContract::class)
                ->give(OrcidPublicationsRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [PublicationsRepositoryContract::class];
    }
}
