#!/usr/bin/env /bin/sh
composer install

mkdir /app/var
touch /app/var/data.db

composer build-database

exec "$@"