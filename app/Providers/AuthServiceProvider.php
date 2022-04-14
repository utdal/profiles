<?php

namespace App\Providers;

use App\Policies\LogPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\SettingPolicy;
use App\Policies\SchoolPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserDelegationPolicy;
use App\LogEntry;
use App\Policies\ProfileStudentPolicy;
use App\Profile;
use App\ProfileStudent;
use App\Setting;
use App\School;
use App\User;
use App\UserDelegation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Tags\Tag;

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
        ProfileStudent::class => ProfileStudentPolicy::class,
        Setting::class => SettingPolicy::class,
        School::class => SchoolPolicy::class,
        Tag::class => TagPolicy::class,
        User::class => UserPolicy::class,
        UserDelegation::class => UserDelegationPolicy::class,
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
