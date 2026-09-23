#!/bin/bash

if [ ! -L public/storage ]; then
    php artisan storage:link
fi

exec php-fpm