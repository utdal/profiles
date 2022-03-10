<?php

namespace App\Traits;

use App\Role;
use App\UserRole;
use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * User Role functions
 */
trait RoleTrait
{
    /**
     * Boot the role trait for the user model
     */
    public static function bootRoleTrait()
    {
        /** @var string The user model class name */
        $user_class = static::class;

        // Remove role relations when deleting a user (except for soft deleted)
        static::deleting(function ($user) use ($user_class) {
            if (!method_exists($user_class, 'bootSoftDeletes')) {
                $user->roles()->sync([]);
            }

            return true;
        });
        
        // Clear role cache after a user is deleted
        static::deleted(function ($user) {
            static::flushCachedRoles();

            return true;
        });

        // Clear role cache when saving/updating a user
        static::saving(function ($user) {
            static::flushCachedRoles();

            return true;
        });
        
        // Clear role cache when a user is restored from a soft delete
        // static::restored(function ($user) {
        //     static::flushCachedRoles();

        //     return true;
        // });

    }

    /**
     * Checks if the user has a role by its name.
     *
     * @param string|array $name       Role name or array of role names.
     * @param bool         $requireAll All roles in the array are required.
     *
     * @return bool
     */
    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Checks if the user or the user's delegator has a role by its name.
     *
     * Override of EntrustUserTrait@hasRole(). If a user does not have the named role,
     * then the check will still return true if the user is a delegate for another user
     * that has that role.
     *
     * @param string|array $role       Role name or array of role names.
     * @param bool         $requireAll All roles in the array are required.
     *
     * @return bool
     */
    public function userOrDelegatorhasRole($role, $requireAll = false)
    {
        if (is_array($role)) {
            foreach ($role as $roleName) {
                $hasRole = $this->hasRole($roleName) || $this->delegatorHasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }
            return $requireAll;
        } elseif ($this->hasRole($role) || $this->delegatorHasRole($role)) {
            return true;
        }
        return false;
    }

    /**
     * Check if any of the user's delegators have a role.
     *
     * @param  string $role Name of the role.
     * @return bool
     */
    public function delegatorHasRole($role)
    {
        $delegators = $this->currentDelegators()->get();
        foreach ($delegators as $delegator) {
            if ($delegator->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if the user has a role option
     *
     * @param string $role_name
     * @param string $option_name
     * @param mixed $value
     * @return bool
     */
    public function hasRoleOption($role_name, $option_name, $value)
    {
        $role_option_value = $this->roleOptions($role_name, $option_name);

        if ($role_option_value === $value || (is_array($role_option_value) && in_array($value, $role_option_value))) {
            return true;
        }

        return false;
    }

    /**
     * Gets any options associated with a user's role assignment
     *
     * @param string $role_name
     * @param string $option_name
     * @return mixed|null
     */
    public function roleOptions($role_name, $option_name)
    {
        $role = $this->cachedRoles()->firstWhere('name', $role_name);

        if ($role === null) {
            return null;
        }

        return Arr::get($role->pivot->options, $option_name);
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $role
     */
    public function attachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
        static::flushCachedRoles();
    }

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $role
     */
    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->detach($role);
        static::flushCachedRoles();
    }

    /**
     * Attach multiple roles to a user
     *
     * @param mixed $roles
     */
    public function attachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * Detach multiple roles from a user
     *
     * @param mixed $roles
     */
    public function detachRoles($roles = null)
    {
        if (!$roles) $roles = $this->roles()->get();

        foreach ($roles as $role) {
            $this->detachRole($role);
        }
    }

    /**
     * Get the user's cached roles
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function cachedRoles()
    {
        $userPrimaryKey = $this->primaryKey;
        $cacheKey = 'entrust_roles_for_user_' . $this->$userPrimaryKey;
        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags('role_user')->remember($cacheKey, Config::get('cache.ttl', 3600), function () {
                return $this->roles()->get();
            });
        } else return $this->roles()->get();
    }

    /**
     * Flush the cached roles
     *
     * @return bool
     */
    public static function flushCachedRoles()
    {
        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags('role_user')->flush();
        }

        return false;
    }

    /**
     *Filtering users according to their role 
     *
     *@param string $role
     *@return users collection
     */
    public function scopeWithRole($query, $role)
    {
        return $query->whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        });
    }

    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
                    ->withPivot('options')
                    ->using(UserRole::class);
    }
}