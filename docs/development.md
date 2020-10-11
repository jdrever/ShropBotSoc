---
layout: default
---
# Development

* Source code at <https://github.com/joejcollins/captain-magenta>.

## Docker Environment

* Start with `docker-compose up`
* Xdebug is installed so add this to the `launch.json`.

```json
      {
          "name": "Listen for XDebug",
          "type": "php",
          "request": "launch",
          "port": 9000,
          "pathMappings": {
              "/var/www/html": "${workspaceFolder}/src"
          },
          "ignore": [
              "**/vendor/**/*.php"
          ],
          "xdebugSettings": {
              "max_data": 65535,
              "show_hidden": 1,
              "max_children": 100,
              "max_depth": 5
          }
      },
```

* Website at <http://localhost:8080/>.
* Documents at <http://localhost:8089/captain-magenta/>.
* To connect `docker exec -it captain-magenta-php /bin/bash`

## WSL Environment

* Start WSL in `/src` with `ubuntu run`
* Install Apache and PHP

```bash
      apt-get install -y \
      apache2 \
      php-intl php-mbstring php-xml php-zip php-xdebug \
      php-mysql mysql-server mysql-client \
      php-pgsql postgresql-$PGSQL_VERSION \
      php-sqlite3 sqlite3 \
      php-memcached memcached \
      php-redis redis-server \
      php-curl curl \
      php-gd php-imagick \
      python-pip
```

* Install composer `apt install composer`
* Using composer install the `vendor` files using `composer install`
* Run the build in PHP development server with `sudo php spark serve --host=0.0.0.0 --port=8080`
