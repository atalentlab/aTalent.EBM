#!/bin/bash
source src/.env

docker exec $APP_NAME"_workspace_1" rm -rf public/apidocs
docker exec -i $APP_NAME"_workspace_1" php artisan apidoc:generate
docker exec $APP_NAME"_workspace_1" sh -c 'cp resources/views/api/docs/* public/apidocs/source/'
docker exec -i $APP_NAME"_workspace_1" php artisan apidoc:generate