#!/usr/bin/env sh
bin/console doctrine:migrations:migrate --no-interaction
bin/console --env=test doctrine:migrations:migrate --no-interaction
