# Setup for Ubuntu 18.04

    sudo apt-get install php
    php -v
    sudo apt-get install php-pear php-fpm php-dev php-zip php-curl php-xmlrpc php-gd php-mysql php-mbstring php-xml php-bcmath php-sqlite3 libapache2-mod-php php-bcmath php-cgi php-xdebug
    sudo apt-get install mysql-server

Create the MySql database like this

    sudo service mysql start
    mysql -u root -p
    mysql> show databases;
    mysql> CREATE DATABASE shropsdb;
    mysql> USE shropsdb;
    mysql> source /mnt/c/Users/username/botanical_records/application/database/test_data.sql;
    mysql> quit;

Then run the built in server.

    php -S localhost:8080 -c debug.ini

If running on the GAE 

    dev_appserver.py --php_executable_path=/usr/bin/php-cgi --support_datastore_emulator=False ./app.yaml

