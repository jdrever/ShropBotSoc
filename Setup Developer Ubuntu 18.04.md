# Setup for Ubuntu 18.04

Assuming...

    Username: user
    Password: password

## Install the LAMP stack

    sudo apt update && sudo apt upgrade
    sudo apt install tasksel
    sudo tasksel install lamp-server
    sudo apt install php-gd

Create change the security on MySql and create a database

    sudo service mysql start
    sudo mysql -u root -p
    mysql> DROP USER 'root'@'localhost';
    mysql> CREATE USER 'root'@'%' IDENTIFIED BY 'password';
    mysql> GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
    mysql> FLUSH PRIVILEGES;
    mysql> show databases;
    mysql> CREATE DATABASE shropsdb;
    mysql> USE shropsdb;
    mysql> source /mnt/c/Users/username/botanical_records/application/database/test_data.sql;
    mysql> quit;

Then run the built in server.

    php -S localhost:8080 -c debug.ini

This server tends to crash with ImageCreateFromPNG() in PHP, so it might be as well to use Apache.

## Running on Apache

Use nano do to the editing….

	sudo nano /etc/apache2/apache2.conf

...and add this to the apache2.conf so symbolic links to your Projects directory with work.

    <Directory /mnt/c/Users/Username/Projects/>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
    </Directory>

...and edit the configuration of the root directory to AllowOverride All

    <Directory /var/www>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
    </Directory>

This is necessary to the rewrite rules in the .htaccess file work.  Of course the rewrite needs to be enabled as well.

    sudo a2enmod rewrite

Add a symbolic link in the /var/www/html directory

    sudo ln -s /mnt/c/Users/username/botanical_records /var/www/html/botanical_records

Start the servers

    sudo service mysql start
    sudo service apache2 start

And visit

    <http://localhost/botanical_records>
