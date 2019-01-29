<?php

namespace App\Ldap\Schemas;

use \Adldap\Schemas\ActiveDirectory;

/**
 * Class ActiveDirectory.
 *
 * The active directory attribute schema for easy auto completion retrieval.
 */
class InstitutionActiveDirectory extends ActiveDirectory
{
    /**
     * {@inheritdoc}
     */
    public function accountName()
    {
        return 'samaccountname';
    }

    /**
     * {@inheritdoc}
     */
    public function accountType()
    {
        return 'samaccounttype';
    }

    /**
     * {@inheritdoc}
     */
    public function commonName()
    {
        return 'cn';
    }

    /**
     * {@inheritdoc}
     */
    public function company()
    {
        return 'college';
    }

    /**
     * {@inheritdoc}
     */
    public function department()
    {
        return 'dept';
    }

    /**
     * {@inheritdoc}
     */
    public function displayName()
    {
        return 'displayName';
    }

    /**
     * {@inheritdoc}
     */
    public function email()
    {
        return 'mail';
    }

    /**
     * {@inheritdoc}
     */
    public function emailNickname()
    {
        return 'pea';
    }

    /**
     * {@inheritdoc}
     */
    public function employeeId()
    {
        return 'jamsid';
    }

    /**
     * {@inheritdoc}
     */
    public function employeeNumber()
    {
        return 'uidNumber';
    }

    /**
     * {@inheritdoc}
     */
    public function firstName()
    {
        return 'givenName';
    }

    /**
     * {@inheritdoc}
     */
    public function member()
    {
        return 'member';
    }

    /**
     * {@inheritdoc}
     */
    public function memberOf()
    {
        return 'memberof';
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'name';
    }

    /**
     * {@inheritdoc}
     */
    public function objectCategory()
    {
        return 'objectclass';
    }

    /**
     * {@inheritdoc}
     */
    public function objectCategoryPerson()
    {
        return 'person';
    }

    /**
     * {@inheritdoc}
     */
    public function objectClass()
    {
        return 'objectclass';
    }

    /**
     * {@inheritdoc}
     */
    public function objectClassPerson()
    {
        return 'inetorgperson';
    }

    /**
     * {@inheritdoc}
     */
    public function otherMailbox()
    {
        return 'pea';
    }

}
