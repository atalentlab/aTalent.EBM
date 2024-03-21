#!/usr/bin/env bash

set -aeuo pipefail

source laradock/.env

docker exec -it $COMPOSE_PROJECT_NAME"_workspace_1" npm $@
