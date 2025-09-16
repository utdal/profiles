<?php

namespace App\Providers;

use App\Helpers\BrowsershotPdfHelper;
use App\Helpers\Contracts\PdfGenerationHelperContract;
use App\Helpers\LambdaPdfHelper;
use App\Macros\CollectionMacros;
use App\Services\PdfGenerationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Setting;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

        $this->app->bind(PdfGenerationHelperContract::class, function () {
            if (config('pdf.driver') === 'lambda') {
                return new LambdaPdfHelper();
            }
            
            return new BrowsershotPdfHelper();
        });
    }
}
