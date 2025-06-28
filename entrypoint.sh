#!/bin/sh

# Jalankan migrasi dan cache config
php /var/www/html/artisan migrate --force
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

# Jalankan supervisor untuk Nginx dan PHP-FPM
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf