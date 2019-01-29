<?php

namespace App;

use App\Profile;
use App\School;
use App\Role;
use App\Traits\RoleTrait;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable
{
    use HasAudits, RoleTrait, Notifiable;

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

        // after a user is created, see if we can attach a school to them
        // grant admin acess to first user record
        static::created(function($user)
        {
            $school = School::WithName($user->college ?: $user->department);
            if ($school->exists()) {
                $user->school()->associate($school->first());
                $user->save();
            }

            if($user->id == 1){
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

    ///////////////////////////////////
    // Mutators & Virtual Attributes //
    ///////////////////////////////////

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
}
