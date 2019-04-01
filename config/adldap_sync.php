<?php

/**
 * OAR-specific LDAP Attribute and property syncing settings.
 *
 * Note: This is not part of the adldap2/adldap2-laravel package.
 * It is specific to this application.
 */

return [

    /**
     * Whether we should sync the user's attributes, roles, and school,
     * locally from LDAP every time they log in.
     */
    'sync_attributes' => env('LDAP_LOGIN_SYNC_ATTRIBUTES', true),
    'sync_roles' => env('LDAP_LOGIN_SYNC_ROLES', true),
    'sync_school' => env('LDAP_LOGIN_SYNC_SCHOOL', true),

    /**
     * Mapping of attributes to sync from LDAP to the User record.
     *
     * Format: ['user model attribute' => 'ldap attribute']
     */
    'attributes' => [
        'name'          => env('LDAP_SYNC_USERNAME', 'samaccountname'),
        'display_name'  => env('LDAP_SYNC_DISPLAYNAME', 'displayname'),
        'college'       => env('LDAP_SYNC_SCHOOL', 'college'),
        'department'    => env('LDAP_SYNC_DEPARTMENT', 'department'),
        'firstname'     => env('LDAP_SYNC_FIRSTNAME', 'givenname'),
        'lastname'      => env('LDAP_SYNC_LASTNAME', 'sn'),
        'email'         => env('LDAP_SYNC_EMAIL', 'mail'),
        'pea'           => env('LDAP_SYNC_EMAILNICKNAME', 'mail'),
        'title'         => env('LDAP_SYNC_TITLE', 'title'),
    ],

    /**
     * Mapping of roles to sync from LDAP to the User record.
     *
     * Format: ['user model attribute' => 'ldap group name']
     */
    'roles' => [
        'staff'     => env('LDAP_SYNC_ROLE_STAFF', 'staff'),
        'student'   => env('LDAP_SYNC_ROLE_STAFF', 'students'),
        'faculty'   => env('LDAP_SYNC_ROLE_STAFF', 'faculty'),
    ],

   /**
     * Default email domain for accounts.
     *
     */
    'email_domain' => env('DEFAULT_EMAIL_DOMAIN', '@example.com'),

];