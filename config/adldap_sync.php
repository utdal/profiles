<?php

/**
 * OAR-specific LDAP Attribute and property syncing settings.
 *
 * Note: This is not part of the adldap2/adldap2-laravel package.
 * It is specific to this application.
 */

return [

    /**
     * Mapping of attributes to sync from LDAP to the User record.
     *
     * Format: ['user model attribute' => 'ldap attribute']
     */
    'attributes' => [
        'name'          => 'samaccountname',
        'display_name'  => 'displayname',
        'college'       => 'college',
        'department'    => 'department',
        'firstname'     => 'givenname',
        'lastname'      => 'sn',
        'email'         => 'mail',
        'title'         => 'title',
        'pea'           => 'mail',
    ],

    /**
     * Mapping of roles to sync from LDAP to the User record.
     *
     * Format: ['ldap attribute' => 'user model attribute']
     */
    'roles' => [
        'staff'     => 'staff',
        'students'   => 'student',
        'faculty'   => 'faculty',
    ],

    /**
     * The LDAP attribute that contains the User's roles.
     *
     * This attribute should return an array when accessed via an adldap User instance.
     */
    // 'role_attribute' => env('ADLDAP_ROLE_ATTRIBUTE', 'edupersonaffiliation'),

   /**
     * Default email domain for accounts.
     *
     */
    'email_domain' => env('DEFAULT_EMAIL_DOMAIN', '@example.com'),

];