<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @var array Mass-assignable attributes */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Boot the Role model
     */
    public static function boot()
    {
        parent::boot();

        /** @var string The name of this class */
        $role_class = static::class;

        // when deleting a role, also delete its many-to-many user relations (except soft deletes)
        static::deleting(function ($role) use ($role_class) {
            if (!method_exists($role_class, 'bootSoftDeletes')) {
                $role->users()->sync([]);
            }

            return true;
        });
    }

    ///////////////
    // Relations //
    ///////////////

    /**
     * Role has many Users.
     * 
     * @return App\User
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
