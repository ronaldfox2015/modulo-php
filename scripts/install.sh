#!/usr/bin/env bash

DOMAIN="local.services.bumeran.com"

if ! grep -q "$DOMAIN" /etc/hosts; then
  echo "##### Host #####"
  sudo su -c "echo '127.0.0.1 $DOMAIN' >> /etc/hosts"
fi

if [ ! -d "./vendor/" ]; then
    echo "##### Composer #####"
    ./scripts/task.composer.sh install --prefer-dist
fi

