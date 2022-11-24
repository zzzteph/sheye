#!/bin/bash

/usr/bin/mysqld --datadir=/var/lib/mysql --user=mysql --skip-networking=0 &
sleep 5
mysql -e "UPDATE mysql.user SET Password = PASSWORD('12345') WHERE User = 'root'"
mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
mysql -e "DROP DATABASE IF EXISTS test;"
mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
mysql -e "create database scanner";
mysql -e "CREATE USER 'scanner'@'localhost' IDENTIFIED BY 'scanner'";
mysql -e "GRANT ALL PRIVILEGES ON scanner . * TO 'scanner'@'localhost'";
mysql -e "FLUSH PRIVILEGES";
cd /var/www/html&& php artisan migrate 
nginx & docker-php-entrypoint php-fpm 