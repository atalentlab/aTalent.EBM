#!/usr/bin/env bash

set -aeuo pipefail

if [ ! -f .env ] || [ ! -f laradock/.env ]; then
    echo "ERROR: Missing laradock/.env or .env file"
    exit
fi

source laradock/.env

./scripts/permissions_folders.sh

cd laradock/

printf "Installing Containers\n"

#If --no-cache option passed regenerate all docker containers
if  [[ ${1-} = "--no-cache" ]]; then
    docker-compose build --no-cache nginx php-fpm mysql workspace
fi

docker-compose up -d nginx php-fpm mysql workspace

printf "Generating self-signed SSL certificate for local development\n"
docker exec -ti $COMPOSE_PROJECT_NAME"_workspace_1" openssl req -x509 -sha256 -newkey rsa:2048 -keyout certs/local.key -out certs/local.pem -days 1024 -nodes -subj '/CN=localhost'

# install composer packages
printf "Installing composer packages\n"
docker exec -ti $COMPOSE_PROJECT_NAME"_workspace_1" composer install

# generate artisan key
printf "Generating Application Key\n"
docker exec -ti $COMPOSE_PROJECT_NAME"_workspace_1" php artisan key:generate

# link local storage directory
printf "Creating storage symlink\n"
docker exec -ti $COMPOSE_PROJECT_NAME"_workspace_1" php artisan storage:link

printf "Creating database\n"
docker exec -i $COMPOSE_PROJECT_NAME"_mysql_1" mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -e "CREATE DATABASE IF NOT EXISTS $MYSQL_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"

# run migrations
printf "Running migrations\n"
docker exec -ti $COMPOSE_PROJECT_NAME"_workspace_1" php artisan migrate

# run db seeder
printf "Seeding database\n"
docker exec -ti $COMPOSE_PROJECT_NAME"_workspace_1" php artisan db:seed

printf "Installing npm packages\n"
docker exec -ti $COMPOSE_PROJECT_NAME"_workspace_1" npm install