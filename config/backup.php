<?php

return [

    'backup' => [

        /*
         * The name of this application. You can use this name to monitor
         * the backups.
         */
        'name' => env('APP_NAME', 'Profiles'),

        'source' => [

            'files' => [

                /*
                 * The list of directories and files that will be included in the backup.
                 */
                'include' => [
                    storage_path('app'),
                ],

                /*
                 * These directories and files will be excluded from the backup.
                 *
                 * Directories used by the backup process will automatically be excluded.
                 */
                'exclude' => [
                    // base_path('vendor'),
                    // base_path('node_modules'),
                ],

                /*
                 * Determines if symlinks should be followed.
                 */
                'follow_links' => true,
            ],

            /*
             * The names of the connections to the databases that should be backed up
             * MySQL, PostgreSQL, SQLite and Mongo databases are supported.
             */
            'databases' => [
                'mysql',
            ],
        ],

        /*
         * The database dump can be compressed to decrease diskspace usage.
         *
         * Out of the box Laravel-backup supplies
         * Spatie\DbDumper\Compressors\GzipCompressor::class.
         *
         * You can also create custom compressor. More info on that here:
         * https://github.com/spatie/db-dumper#using-compression
         *
         * If you do not want any compressor at all, set it to null.
         */
        'database_dump_compressor' => \Spatie\DbDumper\Compressors\GzipCompressor::class,

        'destination' => [

            /*
             * The filename prefix used for the backup zip file.
             */
            'filename_prefix' => 'profiles_' . env('APP_ENV') . '_',

            /*
             * The disk names on which the backups will be stored.
             */
            'disks' => [
                // 'local',
                's3',
            ],
        ],

        /*
         * The directory where the temporary files will be stored.
         */
        'temporary_directory' => env('BACKUP_TEMP_PATH', storage_path('app/backup-temp')),
    ],

    /*
     * You can get notified when specific events occur. Out of the box you can use 'mail' and 'slack'.
     * For Slack you need to install guzzlehttp/guzzle.
     *
     * You can also use your own notification classes, just make sure the class is named after one of
     * the `Spatie\Backup\Events` classes.
     */
    'notifications' => [

        'notifications' => [
            \Spatie\Backup\Notifications\Notifications\BackupHasFailed::class         => ['mail'],
            \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFound::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupHasFailed::class        => ['mail'],
            \Spatie\Backup\Notifications\Notifications\BackupWasSuccessful::class     => ['mail'],
            \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFound::class   => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessful::class    => ['mail'],
        ],

        /*
         * Here you can specify the notifiable to which the notifications should be sent. The default
         * notifiable will use the variables specified in this config file.
         */
        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

        'mail' => [
            'to' => env('BACKUP_EMAIL_NOTIFICATION', 'email@example.com'),
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'do_not_reply@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Profiles'),
            ],
        ],

        'slack' => [
            'webhook_url' => '',

            /*
             * If this is set to null the default channel of the webhook will be used.
             */
            'channel' => null,

            'username' => null,

            'icon' => null,
        ],
    ],

    /*
     * Here you can specify which backups should be monitored.
     * If a backup does not meet the specified requirements the
     * UnHealthyBackupWasFound event will be fired.
     */
    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'Profiles'),
            'disks' => ['s3'],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => env('BACKUP_HEALTHCHECK_AGE_DAYS', 1),
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => env('BACKUP_HEALTHCHECK_STORAGE_LIMIT', 500000),
            ],
        ],

        /*
        [
            'name' => 'name of the second app',
            'disks' => ['local', 's3'],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 5000,
            ],
        ],
        */
    ],

    'cleanup' => [
        /*
         * The strategy that will be used to cleanup old backups. The default strategy
         * will keep all backups for a certain amount of days. After that period only
         * a daily backup will be kept. After that period only weekly backups will
         * be kept and so on.
         *
         * No matter how you configure it the default strategy will never
         * delete the newest backup.
         */
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

        'default_strategy' => [

            /*
             * The number of days for which backups must be kept.
             */
            'keep_all_backups_for_days' => env('BACKUP_KEEP_ALL_FOR_DAYS', 90),

            /*
             * The number of days for which daily backups must be kept.
             */
            'keep_daily_backups_for_days' => env('BACKUP_KEEP_DAILY_FOR_DAYS', 90),

            /*
             * The number of weeks for which one weekly backup must be kept.
             */
            'keep_weekly_backups_for_weeks' => env('BACKUP_KEEP_WEEKLY_FOR_WEEKS', 24),

            /*
             * The number of months for which one monthly backup must be kept.
             */
            'keep_monthly_backups_for_months' => env('BACKUP_KEEP_MONTHLY_FOR_MONTHS', 12),

            /*
             * The number of years for which one yearly backup must be kept.
             */
            'keep_yearly_backups_for_years' => env('BACKUP_KEEP_YEARLY_FOR_YEARS', 5),

            /*
             * After cleaning up the backups remove the oldest backup until
             * this amount of megabytes has been reached.
             */
            'delete_oldest_backups_when_using_more_megabytes_than' => env('BACKUP_KEEP_TOTAL_MB', 25000),
        ],
    ],
];
