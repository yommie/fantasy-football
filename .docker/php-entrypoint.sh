#!/usr/bin/env /bin/sh
composer install

touch /app/var/data.db

composer build-database

exec "$@"