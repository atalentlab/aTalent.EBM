#!/usr/bin/env bash

set -aeuo pipefail

source laradock/.env

docker exec -it $COMPOSE_PROJECT_NAME"_mysql_1" mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE
