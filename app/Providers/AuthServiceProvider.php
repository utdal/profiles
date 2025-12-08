<?php

namespace App\Providers;

use App\Bookmark;
use App\Policies\LogPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\SettingPolicy;
use App\Policies\SchoolPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserDelegationPolicy;
use App\LogEntry;
use App\Policies\ProfileStudentPolicy;
use App\Policies\UserBookmarkPolicy;
use App\Profile;
use App\ProfileStudent;
use App\Setting;
use App\School;
use App\User;
use App\UserDelegation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Cache;
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
        Bookmark::class => UserBookmarkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        
        Gate::define('requestPdfDownload', function ($user) {
            return $user->hasRole(['site_admin', 'students_admin', 'student', 'faculty']);
        });

        Gate::define('downloadPdf', function ($user, $token) {
            $key = "pdf:tokens:{$user->pea}";
            
             if (Cache::has($key)) {
                $user_tokens = Cache::get($key);
                return $user->hasRole(['site_admin', 'students_admin', 'student', 'faculty']) && $user_tokens->contains($token);
            }
        });
        //
    }
}
