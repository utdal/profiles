<?php

namespace App;

use App\Bookmark;
use App\Profile;
use App\Role;
use App\School;
use App\Student;
use App\UserSetting;
use App\Traits\RoleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable
{
    use HasFactory, HasAudits, RoleTrait, Notifiable;

    /** @var array The attributes that are mass-assignable */
    protected $fillable = [
        'name',
        'display_name',
        'department',
        'firstname',
        'lastname',
        'pea',
        'email',
        'title',
        'college',
        'school_id',
    ];

    /** @var array User columns to auto-cast to Carbon instances */
    protected $dates = ['created_at', 'updated_at', 'last_access'];

    /** @var array The attributes excluded from the model's JSON form. */
    protected $hidden = ['remember_token'];

    /** @var array The attributes excluded from activity logging. */
    protected $auditExclude = ['remember_token', 'password'];

    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // grant admin acess to first user record
        static::created(function($user)
        {
            if($user->id == 1) {
                $user->roles()->attach(Role::where('name','site_admin')->first()->id);
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'pea';
    }

    //////////////////////
    // General Methods  //
    //////////////////////

    /**
     * Determine if this User owns the given model.
     * 
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  bool $check_delegators also check if the delegator(s) owns the given model
     * @return bool
     */
    public function owns($model, $check_delegators = false)
    {
        if (($model instanceof User) && $this->is($model)) {
            return true;
        }
        if (method_exists($model, 'user') && $model->user()->whereId($this->id)->exists()) {
            return true;
        }
        if (method_exists($model, 'users') && $model->users()->whereId($this->id)->exists()) {
            return true;
        }

        if ($check_delegators) {
            foreach ($this->currentDelegators as $delegator) {
                if ($delegator->owns($model)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine if this User has bookmarked the given model
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function hasBookmarked($model)
    {
        if ($this->relationLoaded('bookmarks')) {
            return $this->bookmarks->contains(function($bookmark, $i) use ($model) {
                return $bookmark->userable_id === $model->getKey() &&
                       $bookmark->userable_type === get_class($model);
            });
        }

        return $this->bookmarked($model)->where('userable_id', '=', $model->getKey())->exists();
    }

    /**
     * Bookmark the given model
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function bookmark($model)
    {
        $this->bookmarked($model)->attach($model);
    }

    /**
     * Un-bookmark the given model
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return int
     */
    public function unbookmark($model)
    {
        return $this->bookmarked($model)->detach($model);
    }

    //////////////////
    // Query Scopes //
    //////////////////

    /**
     * Query scope to get users associated with a particular school id
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|array $school : the ID of the school
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSchool($query, $schools)
    {
        $schools = (array) $schools;

        return $query->whereIn('school_id', $schools)
            ->orWhereHas('setting', function ($q) use ($schools) {
                foreach ($schools as $i => $school) {
                    $q->whereNotNull('additional_schools->' . (int)$school, $i === 0 ? 'and' : 'or');
                }
            });
    }

    /**
     * Query scope to get users associated with a particular school name
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array $school_names : the name(s) of the school
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSchoolNamed($query, $school_names)
    {
        $school_names = (array) $school_names;

        return $query
            ->whereHas('school', function ($q) use ($school_names) {
                $q->withNames($school_names);
            })
            ->orWhereHas('setting', function ($q) use ($school_names) {
                $q->joinSub(School::withNames($school_names), 'schools', function ($j) {
                    $j->whereRaw("JSON_CONTAINS(JSON_KEYS(`additional_schools`), JSON_QUOTE(CAST(`schools`.`id` as char)))");
                });
            });
    }

    /**
     * Query scope to get users with a particular department
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array $department
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithDepartment($query, $departments)
    {
        $departments = (array) $departments;

        return $query->whereIn('department', $departments)
            ->orWhereHas('setting', function ($q) use ($departments) {
                foreach ($departments as $i => $department) {
                    $q->whereRaw(
                        "JSON_CONTAINS(JSON_EXTRACT(additional_departments, '$'), ?)",
                        ["\"{$department}\""],
                        $i === 0 ? 'and' : 'or'
                    );
                }
            });
    }

    ///////////////////////////////////
    // Mutators & Virtual Attributes //
    ///////////////////////////////////

    /**
     * Virtual attribute to get additional schools for a user
     *
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getAdditionalSchoolsAttribute()
    {
        return $this->setting->additional_schools ?? null;
    }

    /**
     * Virtual attribute to get additional departments for a user
     *
     * @return array|null
     */
    public function getAdditionalDepartmentsAttribute()
    {
        return $this->setting->additional_departments ?? null;
    }

    /**
     * Virtual attribute to get all schools for a user
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSchoolsAttribute()
    {
        return collect([$this->school])->merge($this->additional_schools ?? [])->filter();
    }

    /**
     * Virtual attribute to get all departments for a user
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDepartmentsAttribute()
    {
        return collect([$this->department])->merge($this->additional_departments ?? []);
    }

    /**
     * Sets the user's pea without the '@domain' part.
     * 
     * @param string $pea
     */
    public function setPeaAttribute($pea)
    {
        $pea_beginning = strstr($pea, '@', true) ?: $pea;
        $this->attributes['pea'] = strtolower($pea_beginning);
    }

    /**
     * Sets the user's school_id.
     * 
     * @param string $id
     */
    public function setSchoolIdAttribute($id)
    {
        if ($id == '') {
            $id = null;
        }
        $this->attributes['school_id'] = $id;
    }

    ///////////////
    // Relations //
    ///////////////

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function studentProfiles()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * User has one UserSetting (one-to-one)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function setting()
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * User has many bookmarks (one to many)
     * 
     * This is for direct access to the pivot class (i.e. all bookmarks of any type),
     * and also allows us to eager-load bookmarks in order to check existence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * User has many bookmarked models (polymorphic many-to-many)
     *
     * @param string|\Illuminate\Database\Eloquent\Model $model : model class name or instance
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function bookmarked($model)
    {
        return $this->morphedByMany(is_object($model) ? get_class($model) : $model, 'userable', 'bookmarks')
            ->using(Bookmark::class)
            ->withTimestamps();
    }

    public function delegations()
    {
        return $this->hasMany(UserDelegation::class, 'delegator_user_id');
    }

    /**
     * User delegates. Many-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function delegates()
    {
        return $this->belongsToMany('App\User', 'user_delegations', 'delegator_user_id', 'delegate_user_id')
                    ->using(UserDelegation::class)
                    ->withPivot('starting', 'until', 'gets_reminders')
                    ->withTimestamps();
    }

    /**
     * Current user delegates.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentDelegates()
    {
        return $this->delegates()
            ->whereDate('user_delegations.starting', '<=', now())
            ->where(function($q) {
                $q->whereDate('user_delegations.until', '>', now());
                $q->orWhereNull('user_delegations.until');
            });
    }

    /**
     * User's current delegates that get reminders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentReminderDelegates()
    {
        return $this->currentDelegates()->where('gets_reminders', '=', true);

    }

    /**
     * Email addresses of the user's current delegates that get reminders.
     *
     * @return array
     */
    public function currentReminderDelegateEmailAddresses()
    {
        return $this->currentReminderDelegates()->pluck('email')->all();
    }

    /**
     * User delegators. Inverse of delegate many-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function delegators()
    {
        return $this->belongsToMany('App\User', 'user_delegations', 'delegate_user_id', 'delegator_user_id')
                    ->using(UserDelegation::class)
                    ->withPivot('starting', 'until', 'gets_reminders')
                    ->withTimestamps();
    }

    /**
     * Current user delegators.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentDelegators()
    {
        return $this->delegators()
            ->whereDate('user_delegations.starting', '<=', now())
            ->where(function($q) {
                $q->whereDate('user_delegations.until', '>', now());
                $q->orWhereNull('user_delegations.until');
            });
    }

    /**
     * User's current delegators that have allowed the user to get reminders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentReminderDelegators()
    {
        return $this->currentDelegators()->where('gets_reminders', '=', true);
    }  
    
    /**
     * Additional roles currently delegated to the user.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function currentDelegatedRoles()
    {
        return Role::query()
            ->wheredoesntHave('users', function ($q) {
                $q->where('id', '=', $this->id);
            })
            ->whereHas('users.delegates', function ($q) {
                $q->where('delegate_user_id', $this->id)
                  ->whereDate('user_delegations.starting', '<=', now())
                  ->where(function($q) {
                    $q->whereDate('user_delegations.until', '>', now())
                      ->orWhereNull('user_delegations.until');
                  });
            });
    }

}