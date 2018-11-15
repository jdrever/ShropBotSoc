# Setup for Ubuntu 18.04

    sudo apt-get install php
    php -v
    sudo apt-get install php-pear php-fpm php-dev php-zip php-curl php-xmlrpc php-gd php-mysql php-mbstring php-xml php-bcmath php-sqlite3 libapache2-mod-php php-bcmath php-cgi php-xdebug

Install sqlite3 and create a database.

    sudo apt-get install sqlite3 php-sqlite3

Convert the MySql database like this

	./mysql2sqlite.sh shropsdb.sql | sqlite3 shropsdb.db

Then run the built in server.

    php -S localhost:8080 -c debug.ini
