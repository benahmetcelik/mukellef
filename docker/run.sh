#!/bin/bash

cd /var/www





php artisan optimize:clear
php artisan optimize
php artisan view:cache
#php artisan migrate
#php artisan migrate --path=database/migrations/Sprint12/
#php artisan migrate --path=database/migrations/Sprint13/
#php artisan migrate --path=database/migrations/Sprint14/
#php artisan migrate --path=database/migrations/Sprint15/
#php artisan migrate --path=database/migrations/Sprint16/
#php artisan db:seed
php artisan storage:link


/usr/bin/supervisord -c /etc/supervisord.conf
