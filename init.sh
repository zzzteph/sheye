#!/bin/bash


cd /var/www/html/ && php artisan key:generate
cd /var/www/html/ && /var/www/html/php artisan migrate
cd /var/www/html/ && /var/www/html/php artisan db:seed --class=ScannerSeeder
cd /var/www/html/ && /var/www/html/php artisan add:user admin admin
sh workers.sh & nginx & docker-php-entrypoint php-fpm 