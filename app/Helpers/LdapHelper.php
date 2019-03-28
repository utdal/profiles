<?php

namespace App\Helpers;

use Adldap\AdldapInterface;
use Adldap\Models\User as LdapUser;
use App\Helpers\Contracts\LdapHelperContract;
use App\Ldap\Handlers\LdapAttributeHandler;
use App\User;

class LdapHelper implements LdapHelperContract
{
    /** @var Adldap instance */
    protected $adldap;

    /**
     * Class constructor.
     */
    public function __construct(AdldapInterface $adldap)
    {
        $this->adldap = $adldap;
    }

    /**
     * Search for users in LDAP.
     * 
     * @param  string $displayname : name to search for
     * @param  array  $fields      : fields to retrieve
     * @return array
     */
    public function search($displayname, array $fields = ['displayname','samaccountname'])
    {
        $query = $this->adldap->getDefaultProvider()->search()->select($fields);

        // Break the search string into fragments and search each fragment
        $displayname = str_replace(",","",$displayname); // get rid of commas
        $displayname = (substr($displayname,-1) === " ") ? [$displayname] : explode(' ',$displayname);
        foreach ($displayname as $name_fragment) {
            $query->orWhereContains('displayname', $name_fragment);
            $query->orWhereContains('uid', $name_fragment);
        }

        // return $this->flattenUserAttributes($query->get(), $fields);
        $users = $query->get();

        // Copy samaccountname attribute to uid attribute, which is missing in AD LDAP
        // @todo Remove this and change the front-end references to samaccountname instead of uid
        $users->transform(function ($user, $key) {
            $user->setFirstAttribute('uid', $user->getFirstAttribute('samaccountname'));
            return $user;
        });

        return $this->flattenUserAttributes($users, $fields);
    }

    /**
     * Flattens the nested array of user attributes into one level.
     * 
     * @param  Illuminate\Support\Collection $users
     * @param  array $fields
     * @return array
     */
    protected function flattenUserAttributes($users, $fields)
    {
        return $users->transform(function($user, $key) use ($fields) { // flatten the user objects
                return array_map(function($attribute) {
                    return $attribute[0];
                }, array_intersect_key($user->getAttributes(), array_flip($fields)));
            })->all();
    }

    /**
     * Look up and return a local user instance by checking LDAP for that user.
     *
     * @param  string $display_name The user's display name
     * @param  string $name         The user's name/uid
     *
     * @return App\User|boolean
     */
    public function getUser($name)
    {
        // $ldap_user = $this->adldap->getDefaultProvider()->search()->whereUid($name)->get()->first();
        $ldap_user = $this->adldap->getDefaultProvider()->search()->whereSamaccountname($name)->get()->first();

        if ($ldap_user && $ldap_user->exists) {
            return $this->getUserFromLdapUser($ldap_user);
        }

        return false;
    }

    /**
     * Finds or creates a new local User based on an LDAP user.
     * 
     * @param  Adldap\Models\User $ldap_user
     * @return App\User
     */
    protected function getUserFromLdapUser($ldap_user)
    {
        // $user = User::firstOrCreate(['name' => $ldap_user->getFirstAttribute('uid')]);
        $user = User::firstOrCreate(['name' => $ldap_user->getFirstAttribute('samaccountname')]);

        // Sync User attributes and roles
        $handler = app(LdapAttributeHandler::class);
        $handler->handle($ldap_user, $user);
        $user->save();

        return $user;
    }

}
