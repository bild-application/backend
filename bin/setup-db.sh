#!/usr/bin/env sh
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate --no-interaction
