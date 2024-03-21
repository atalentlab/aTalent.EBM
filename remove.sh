#!/usr/bin/env bash

source laradock/.env

# remove containers, images and volumes created by this project

bash ./stop.sh

docker container rm $(docker container ls -aq --filter "name=$COMPOSE_PROJECT_NAME")
docker volume rm $(docker volume ls -q --filter="name=$COMPOSE_PROJECT_NAME")
docker image rm -f $(docker images $COMPOSE_PROJECT_NAME* -q)