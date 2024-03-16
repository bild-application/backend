#!/usr/bin/env sh
bin/console --env=test doctrine:schema:drop --force
bin/console --env=test doctrine:database:drop --force
bin/console --env=test doctrine:database:create
bin/console --env=test doctrine:migrations:migrate --no-interaction

