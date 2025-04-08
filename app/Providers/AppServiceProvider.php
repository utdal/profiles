<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Setting;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

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
        Paginator::useBootstrap();
        
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

        Builder::macro('toCsv', function ($name = null) {
            $query = $this;

            return response()->streamDownload(function () use ($query) {
                $results = $query->get();

                if ($results->count() < 1) return;

                $titles = implode(',', array_keys((array) $results->first()->getAttributes()));

                $values = $results->map(function ($result) {
                    return implode(',', collect($result->getAttributes())->map(function ($thing) {
                        return '"'.$thing.'"';
                    })->toArray());
                });

                $values->prepend($titles);

                echo $values->implode("\n");
            }, $name ?? 'export.csv');
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
        }
    }
}
