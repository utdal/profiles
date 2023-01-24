<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;
use App\Setting;
use App\Interfaces\PublicationsApiInterface;
use App\Profile;
use Collective\Html\FormFacade as Form;
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

        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Form::component('inlineErrors', 'errors.inline', ['field_name']);

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
            'students.about',
            'emails.template',
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
        if ($this->app->environment(['local', 'dev'])) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
            $this->app->bind(PublicationsApiInterface::class, function() {
                return PublicationsApiServiceProvider::class;
            });
        }
    }
}
