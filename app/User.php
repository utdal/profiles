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
     * @param  Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function owns($model)
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

        return false;
    }

    /**
     * Determine if this User has bookmarked the given model
     *
     * @param  Illuminate\Database\Eloquent\Model $model
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
     * @param  Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function bookmark($model)
    {
        return $this->bookmarked($model)->attach($model);
    }

    /**
     * Un-bookmark the given model
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     * @return void
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
     * @param string $school_name : the name of the school
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSchoolNamed($query, $school_name)
    {
        return $query
            ->whereHas('school', function ($q) use ($school_name) {
                $q->withName($school_name);
            })
            ->orWhereHas('setting', function ($q) use ($school_name) {
                $q->joinSub(School::withName($school_name), 'schools', function ($j) {
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
     * @return \Illuminate\Support\Collection
     */
    public function getAdditionalSchoolsAttribute()
    {
        return $this->setting->additional_schools ?? null;
    }

    /**
     * Virtual attribute to get additional departments for a user
     *
     * @return \Illuminate\Support\Collection
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

}
