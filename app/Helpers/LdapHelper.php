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

    /** @var App\Ldap\Handlers\LdapAttributeHandler */
    public $handler;

    public $schema;

    public $displayname_attribute;

    public $username_attribute;

    /**
     * Class constructor.
     */
    public function __construct(AdldapInterface $adldap)
    {
        $this->adldap = $adldap;
        $this->schema = $adldap->getDefaultProvider()->getSchema();
        $this->handler = app(LdapAttributeHandler::class);
        $this->username_attribute = $this->schema->loginName();
        $this->displayname_attribute = $this->schema->displayName();
    }

    /**
     * Search for users in LDAP.
     * 
     * @param  string $displayname : name to search for
     * @param  array  $fields      : fields to retrieve
     * @return array
     */
    public function search($displayname, array $fields = [], $to_array = false)
    {
        if (empty($fields)) {
            $fields = [$this->username_attribute, $this->displayname_attribute];
        }

        $query = $this->adldap->getDefaultProvider()->search()->select($fields);

        // Break the search string into fragments and search each fragment
        $displayname = str_replace(",","",$displayname); // get rid of commas
        $displayname = (substr($displayname,-1) === " ") ? [$displayname] : explode(' ',$displayname);
        foreach ($displayname as $name_fragment) {
            $query->orWhereContains($this->displayname_attribute, $name_fragment);
            $query->orWhereContains($this->username_attribute, $name_fragment);
        }

        if ($to_array) {
            return $this->flattenUserAttributes($query->get(), $fields);
        }

        return $query->get();
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
        $ldap_user = $this->adldap->getDefaultProvider()->search()
                          ->where($this->username_attribute, $name)->get()->first();

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
        $user = User::firstOrCreate([
            'name' => $ldap_user->getFirstAttribute($this->username_attribute),
        ]);

        // Sync User attributes and roles
        $this->handler->handle($ldap_user, $user);

        return $user;
    }

}
