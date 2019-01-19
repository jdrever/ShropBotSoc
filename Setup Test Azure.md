# Setup for Acceptance Tests on Azure

Commits to the develop branch on <https://github.com/joejcollins/captain-blue.git> are monitored by an Azure Pipeline at <https://joejcollins.visualstudio.com/captain-blue> which deploys to <https://captain-blue.azurewebsites.net/>.  The website is on free service which provides 60 CPU minutes per day, 1 GB RAM and 1 GB of storage.

## Application Setup

Set up on <https://portal.azure.com/#home> as an App Service, the details of which can be seen at <https://captain-blue.scm.azurewebsites.net/>.  If it needs to be set up again the principle application settings are:

* PHP 7.2
* CI_ENV = testing
* PHP_INI_SCAN_DIR = d:\home\site\ini
* WEBSITE_MYSQL_PASSWORD = **SECRET-MYSQL**

## MySQL In App

The MySql database is provided In App.  More info at <https://github.com/projectkudu/kudu/wiki/MySQL-in-app>.  The data base can be managed via phpMyAdmin.

* <https://captain-blue.scm.azurewebsites.net/phpMyAdmin/index.php>
* User Id=azure;
* Password="**SECRET-MYSQL**"

The setup the data the zipped MySql dump file was transferred via FTP.  The FTP settings are in the "publish profile".

* publishUrl="ftp://waws-prod-cw1-011.ftp.azurewebsites.windows.net/site/wwwroot"
* userName="captain-blue\$captain-blue" 
* userPWD="**SECRET-FTP**"

Once the file has been transferred it can be unzipped and restored (using the console for the App Service that's built into the Azure Portal) to the database like this.

    "D:\Program Files (x86)\mysql\5.7.9.0\bin\mysql.exe" -e "USE localdb;source shropsdb.sql;"  --user=azure --password=**SECRET-MYSQL** --port=**SECRET-PORT** --bind-address=127.0.0.1
