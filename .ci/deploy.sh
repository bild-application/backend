git reset --hard origin/main
composer install --optimize-autoloader
composer dump-env prod
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console lexik:jwt:generate-keypair --skip-if-exists
php bin/console cache:clear
