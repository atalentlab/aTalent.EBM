#!/usr/bin/env bash

set -aeuo pipefail

source laradock/.env

docker exec -ti -u laradock $COMPOSE_PROJECT_NAME"_workspace_1" $@
