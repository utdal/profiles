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
        if ($this->shouldSyncAttributes()) {
            $this->syncUserAttributes($ldap_user, $user);
        }

        $this->normalizeUserAttributes($ldap_user, $user);
        $user->save();

        if ($this->shouldSyncRoles()) {
            $this->syncUserRoles($ldap_user, $user);
        }

        if ($this->shouldSyncSchool()) {
            $this->syncUserSchool($ldap_user, $user);
        }
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
    }

    /**
     * Sets some important default attributes for ldap Users that don't have them set.
     * 
     * @param  User   $user
     * @return void
     */
    protected function normalizeUserAttributes(LdapUser $ldap_user, User $user)
    {
        // If they don't have a name, set it to their login name
        if (!$user->name) {
            $user->name = $ldap_user->getFirstAttribute($this->getLoginAttributeName());
        }

        // If they don't have an email address, set it to name@example.com
        if (!$user->email && $user->name) {
            $user->email = $user->name . $this->getEmailDomain();
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
        $ldap_roles = $ldap_user->getGroupNames();

        if (!is_array($ldap_roles)) {
            return;
        }

        foreach ($this->getRoleMap() as $app_role => $role) {
            $user_has_role = $user->hasRole($app_role);
            $ldap_has_role = in_array($app_role, $ldap_roles);
            $app_role = Role::whereName($app_role)->first();

            if ($user_has_role && !$ldap_has_role) {
                $user->detachRole(Role::whereName($app_role)->first());
            }
            if (!$user_has_role && $ldap_has_role) {
                $user->attachRole($app_role);
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
        $ldap_school = $ldap_user->getFirstAttribute($ldap_user->getSchema()->college());
        $ldap_department = $ldap_user->getFirstAttribute($ldap_user->getSchema()->department());

        if ($ldap_school || $ldap_department) {
            $school = School::WithName($ldap_school ?: $ldap_department);

            if ($school->exists()) {
                $user->school()->associate($school->first());
            }
        }
    }

    /**
     * Should we sync LDAP user attributes to local user on login?
     *
     * @return bool
     */
    protected function shouldSyncAttributes()
    {
        return config('adldap_sync.sync_attributes', true);
    }

    /**
     * Should we sync LDAP user roles to local user on login?
     *
     * @return bool
     */
    protected function shouldSyncRoles()
    {
        return config('adldap_sync.sync_roles', true);
    }

    /**
     * Should we sync LDAP user school to local user on login?
     *
     * @return bool
     */
    protected function shouldSyncSchool()
    {
        return config('adldap_sync.sync_school', true);
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

    /**
     * Get the name of the LDAP attribute used for login
     *
     * @return string
     */
    public function getLoginAttributeName()
    {
        return config('adldap_auth.usernames.ldap', 'samaccountname');
    }

    /**
     * Get the default email domain to use if the user doesn't have
     * an email address specified.
     *
     * @return string
     */
    protected function getEmailDomain()
    {
        return config('adldap_sync.email_domain', '@example.com');
    }

}