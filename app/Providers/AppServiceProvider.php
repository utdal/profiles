<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Setting;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\TaggableStore;
use Spatie\Tags\Tag;

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

        // Renaming, creating or deleting a tag (from any code path) invalidates
        // the cached tag lists and tag pages. Pivot syncs don't fire these events,
        // so TagsController@update and TagsModal flush explicitly.
        // Guarded so seeding/tinker still work on stores without tag support (e.g. the file driver).
        $flush_tag_cache = function () {
            if (Cache::getStore() instanceof TaggableStore) {
                Cache::tags(['profile_tags'])->flush();
            }
        };
        Tag::saved($flush_tag_cache);
        Tag::deleted($flush_tag_cache);

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
