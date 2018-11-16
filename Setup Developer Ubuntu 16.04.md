# Setup for Ubuntu 16.04

## Install Apache and PHP

	sudo add-apt-repository ppa:ondrej/php
	sudo apt update
	sudo apt upgrade
	sudo apt-get install -y apache2
	sudo apt-get install -y mysql-server
	sudo apt install -y php5.6 libapache2-mod-php5.6 php5.6-curl php5.6-gd php5.6-mbstring php5.6-mcrypt php5.6-mysql php5.6-xml php5.6-xmlrpc
	sudo apt install php-pear 
	sudo apt install zip unzip php5.6-zip
	sudo apt-get install php-xdebug

Confirm that PHP 7 is not present and PHP 5 is

	sudo a2dismod php7.0
		*This will, and should, return a "does not exist!" message.*

	sudo a2enmod php5.6
 		*This will, and should, return a "already enabled" message.*

Start Apache, 

	sudo service apache2 start
	
This might give you a warning (which you can ignore)

	[Thu Nov 15 14:21:50.244155 2018] [core:warn] [pid 535] (92)Protocol not available: AH00076: Failed to enable APR_TCP_DEFER_ACCEPT
	
Start MySql

	sudo service mysql start
	mysql -u root -p
	mysql> show databases;
	mysql> CREATE DATABASE ers_school;
	mysql> USE ers_school;
	mysql> source /mnt/c/Users/joejc/Documents/Projects/jjc_www_php_shropshire_botany/application/database/shropsdb.sql
	mysql> quit;
	
Change the Apache conf to follow symbolic links.  The Apache configuration looks like this.

	/etc/apache2/
	|-- apache2.conf
	|       `--  ports.conf
	|-- mods-enabled
	|       |-- *.load
	|       `-- *.conf
	|-- conf-enabled
	|       `-- *.conf
	|-- sites-enabled
	|       `-- *.conf


Use nano do to the editing….

	sudo nano /etc/apache2/apache2.conf

…and add this to the apache2.conf so symbolic links to your Projects directory with work.

	<Directory /mnt/c/Users/joejc/Documents/Projects/>
	        Options Indexes FollowSymLinks
	        AllowOverride None
	        Require all granted
	</Directory>


Add a symbolic link in the /var/www/html directory

	cd /var/www/html
	sudo ln -s /mnt/c/Users/joejc/Documents/Projects/jjc_www_php_shropshire_botany sbtest

Start Apache and test

	sudo service mysql start
	sudo service apache2 start
	http://localhost/sbtest


## Setting up VSCode using WSL

1.	Install vscode https://code.visualstudio.com/.
2.	Add the PHP debug extension https://github.com/felixfbecker/vscode-php-debug
3.	Add the PHP formatter extension https://github.com/kokororin/vscode-phpfmt 
4.	Add this launch.json to the .vscode directory

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

5.	And add this to the workplace settings.json
 
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

7.	Add this to the bottom of the php.ini 

	sudo nano /etc/php/5.6/apache2/php.ini
	
And add this to the bottom.

    [XDebug]
    xdebug.remote_enable = 1
    xdebug.remote_autostart = 1

Xdebug was installed earlier so it should be available.
