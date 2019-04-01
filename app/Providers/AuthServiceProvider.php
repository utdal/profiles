<?php

namespace App\Providers;

use App\Policies\LogPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\SettingPolicy;
use App\Policies\SchoolPolicy;
use App\Policies\UserPolicy;
use App\LogEntry;
use App\Profile;
use App\Setting;
use App\School;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        LogEntry::class => LogPolicy::class,
        Profile::class => ProfilePolicy::class,
        Setting::class => SettingPolicy::class,
        School::class => SchoolPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
