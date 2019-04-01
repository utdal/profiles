<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Setting;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //register modified paginator view as default
        Paginator::defaultView('vendor.pagination.default');
        Paginator::defaultSimpleView('vendor.pagination.simple-default');

        View::composer([
            'layout',
            'home',
            'nav',
            'faq',
            'auth.login',
            'users.index',
            'users.panel',
            'users.create',
            'users.edit',
        ], function ($view) {
            $settings = Cache::rememberForever('settings', function(){
                return Setting::pluck('value', 'name')->toArray();
            });
            view()->share('settings', $settings);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
