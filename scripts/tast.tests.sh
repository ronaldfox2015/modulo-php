#!/usr/bin/env bash

export DEV_UID=$(id -u)
export DEV_GID=$(id -g)

docker-compose -f docker-compose.tests.yml down \
&& docker-compose -f docker-compose.tests.yml run --rm cli