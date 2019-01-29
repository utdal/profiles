<?php

namespace App\Ldap\Handlers;

use Adldap\Models\User as LdapUser;
use App\Role;
use App\School;
use App\User;

class LdapAttributeHandler
{
    /**
     * Sync things from an Ldap User to a local User.
     *
     * @param LdapUser  $ldapUser
     * @param User      $user
     */
    public function handle(LdapUser $ldap_user, User $user)
    {
        $this->syncUserAttributes($ldap_user, $user);
        $this->syncUserRoles($ldap_user, $user);
        $this->syncUserSchool($ldap_user, $user);
    }

    /**
     * Sync a sub-set of the user's ldap attributes to the User model.
     * 
     * @param  LdapUser $ldap_user
     * @param  User     $user
     */
    public function syncUserAttributes(LdapUser $ldap_user, User $user)
    {
        foreach ($this->getAttributeMap() as $user_attribute => $ldap_attribute) {
            $user->$user_attribute = $ldap_user->getFirstAttribute($ldap_attribute);
        }

        $this->normalizeUserAttributes($user);

        $user->save();
    }

    /**
     * Sets some important default attributes for ldap Users that don't have them set.
     * 
     * @param  User   $user
     * @return void
     */
    protected function normalizeUserAttributes(User $user)
    {
        // If they don't have an email address, set it to name@example.com
        if (!$user->email && $user->name) {
            $user->email = $user->name . config('adldap_sync.email_domain');
        }

        // If they don't have a pea, set it to their name
        if (!$user->pea && $user->name) {
            $user->pea = $user->name;
        }

        // If they don't have a display_name, set it to their name
        if (!$user->display_name && $user->name) {
            $user->display_name = $user->name;
        }
    }

    /**
     * Sync a sub-set of the user's roles with the provided list.
     *
     * @param  LdapUser $ldap_user
     * @param  User     $user
     */
    protected function syncUserRoles(LdapUser $ldap_user, User $user)
    {
        $role_attribute = $this->getRoleAttribute();
        $ldap_roles = $ldap_user->getAttribute($role_attribute);

        foreach ($this->getRoleMap() as $role => $app_role) {
            $user_has_role = $user->hasRole($app_role);
            if (!is_null($ldap_roles)) {
                $ldap_has_role = in_array($role, $ldap_roles);
                $app_role = Role::whereName($app_role)->first();
                if ($user_has_role && !$ldap_has_role) {
                    $user->detachRole($app_role);
                }
                if (!$user_has_role && $ldap_has_role) {
                    $user->attachRole($app_role);
                }
            }
        }
    }

    /**
     * Associate the proper school with the user.
     * 
     * @param  LdapUser $ldap_user
     * @param  User     $user
     */
    protected function syncUserSchool(LdapUser $ldap_user, User $user)
    {
        $ldap_school = $ldap_user->getFirstAttribute('college');
        $ldap_department = $ldap_user->getFirstAttribute('dept');
        $school = School::WithName($ldap_school ?: $ldap_department);

        if ($school->exists()) {
            $user->school()->associate($school->first());
        }
    }

    /**
     * Get the LDAP attribute corresponding to roles.
     * 
     * @return string
     */
    protected function getRoleAttribute()
    {
        return config('adldap_sync.role_attribute', 'edupersonaffiliation');
    }

    /**
     * Get the mapping of Ldap role to App role
     * 
     * @return array : ['ldap role' => 'app role']
     */
    protected function getRoleMap()
    {
        return config('adldap_sync.roles', []);
    }

    /**
     * Get the mapping of User attribute to Ldap attribute
     * 
     * @return array : ['user attribute' => 'ldap attribute']
     */
    protected function getAttributeMap()
    {
        return config('adldap_sync.attributes', []);
    }

}