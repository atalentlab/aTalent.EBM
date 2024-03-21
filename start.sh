#!/usr/bin/env bash

set -aeuo pipefail

cd laradock

docker-compose up -d nginx mysql workspace