#!/bin/bash

# Install normal packages
sudo apt update && sudo apt install \
  apache2 \
  php-intl php-mbstring php-xml php-zip php-xdebug \
  php-mysql mysql-server mysql-client \
  php-pgsql postgresql-$PGSQL_VERSION \
  php-sqlite3 sqlite3 \
  php-memcached memcached \
  php-redis redis-server \
  php-curl curl \
  php-gd php-imagick

# Make logging into shells quieter
touch ~/.hushlogin

echo '[XDebug]' >> /etc/php/7.4/apache2$/php.ini

    xdebug.remote_enable = 1
    xdebug.remote_autostart = 1