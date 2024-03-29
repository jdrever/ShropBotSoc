---
layout: default
title: Development
navigation_weight: 2
---
# Development

* Source code at <https://github.com/joejcollins/captain-magenta>.
* Edit a couple of properties in `src/.env` like this... 

```env
CI_ENVIRONMENT = development
app.baseURL = 'https://localhost:8080/'
```

## Gitpod Environment

Got to <https://gitpod.io/#https://github.com/joejcollins/captain-magenta>.

## Docker Environment

Using a Docker container which maps to files on the host.

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
* To connect `docker exec -it captain-magenta-php /bin/bash`

## WSL Ubuntu 20.04 Environment

Using the WSL Linux file system and not synchronizing or sharing files with the host.

* Start WSL with `ubuntu`
* Clone the repo with `https://github.com/joejcollins/captain-magenta`
* Install the requirements with `sh ./ubuntu-20.04-config.sh`
* Xdebug is installed so add this to the `launch.json`.

```json
{
    "name": "Listen for WSL XDebug",
    "type": "php",
    "request": "launch",
    "port": 9009,
    "pathMappings": {
        "${workspaceFolder}/src": "${workspaceFolder}/src"
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
* Ensure that Apache is running with `sudo service apache2 restart`
* Website at <http://localhost:8089/>.

## PHP Built in Development Server

Codeigniter can use the built in PHP server started like this `sudo php spark serve --host=0.0.0.0 --port=8080`.
