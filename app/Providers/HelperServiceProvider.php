<?php

namespace App\Providers;

use Adldap\AdldapInterface;
use App\Helpers\Contracts\LdapHelperContract;
use App\Helpers\LdapHelper;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider implements DeferrableProvider
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
        $this->app->singleton(LdapHelperContract::class, function ($app) {
            return new LdapHelper($app[AdldapInterface::class]);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [LdapHelperContract::class];
    }
}
