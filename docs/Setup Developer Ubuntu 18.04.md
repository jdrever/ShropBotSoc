# Setup for Ubuntu 18.04 (on Windows Subsystem for Linux)

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
    mysql> CREATE DATABASE captainblue;
    mysql> USE captainblue;
    mysql> source /mnt/c/Users/username/captain-blue/application/database/test_data.sql;
    mysql> quit;

Edit the configuration file `application\config\development\database.php` to include these lines so the database connects.

	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'password',
	'database' => 'captainblue',

Configure WSL so the caching works (otherwise you get a `PHP chmod(): Operation not permitted` error).  Create and open 
`/etc/wsl.conf` and add the following:

    [automount]
    options = "metadata"

Then run the built in server.

    sudo php -S localhost:8080 -c debug.ini

This server tends to crash with ImageCreateFromPNG() in PHP, so it might be as well to use Apache.

## Running on Apache because ImageCreateFromPNG() crashes

Use nano do to the editing….

	sudo nano /etc/apache2/apache2.conf

...and add this to the apache2.conf so symbolic links to your Projects directory will work.

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

This is necessary for the rewrite rules in the `.htaccess` file work.  Of course the rewrite needs to be enabled as well.

    sudo a2enmod rewrite

Add a symbolic link in the `/var/www/html` directory

    sudo ln -s /mnt/c/Users/username/captain-blue /var/www/html/captain-blue

Make sure the cache is writable

    sudo umount /mnt/c
    sudo mount -t drvfs C: /mnt/c -o metadata
    sudo chmod -R 777 ./application/cache

Start the servers

    sudo service mysql start
    sudo service apache2 start

And visit

    <http://localhost/captain-blue>

## Debugging with VSCode

1. Install vscode https://code.visualstudio.com/.
2. Add the PHP debug extension https://github.com/felixfbecker/vscode-php-debug
3. Add the PHP formatter extension https://github.com/kokororin/vscode-phpfmt 
4. Add this launch.json to the .vscode directory

    {
        "version": "0.2.0",
        "configurations": [
            {
                "name": "Listen for XDebug",
                "type": "php",
                "request": "launch",
                "port": 9000,
                // server -> local
                "pathMappings": {
                    "/mnt/c/": "c:/",
                }
            }
        ]
    }

5. And add this to the workplace settings.json

    {
        "php.validate.executablePath": "C:\\Users\\joejc\\php.cmd",
        "phpfmt.cakephp": true,
        "phpfmt.php_bin": "C:\\Users\\joejc\\php.cmd"
    }

6.	Put this php.cmd some place handy so it can be used to access PHP no the WSL from Windows and vscode.

    @echo OFF
    setlocal ENABLEDELAYEDEXPANSION

    :: Collect the arguments and replace:
    :: '\' with '/'
    :: 'c:' with 'mnt/c'
    :: The original version replace '"' with '\"' but I found that
    :: clashed with the PHP Formatter which was passing in a PHP 
    :: command and expecting a response.
    set v_params=%*
    set v_params=%v_params:\=/%
    set v_params=%v_params:C:=/mnt/c%
    set v_params=%v_params%
    :: set v_params=%v_params:"=\"%

    :: Call the windows-php inside WSL.
    :: windows-php is just a script which passes the arguments onto
    :: the original php executable and converts its output from UNIX
    :: syntax to Windows syntax.
    C:\Windows\System32\wsl.exe php %v_params%

7. Install Xdebug

    sudo apt-get install php-xdebug

8. If you are using Apache open the php.ini

    sudo nano /etc/php/7.2/apache2/php.ini

    And add this to the bottom.

    [XDebug]
    xdebug.remote_enable = 1
    xdebug.remote_autostart = 1

9. Then restart Apache

    sudo service apache2 restart