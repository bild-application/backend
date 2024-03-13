# Tester

## Mettre en place la BDD de test

Exécuter dans le container php les commandes suivantes :

```
php bin/console --env=test doctrine:database:drop --force
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:migrations:migrate --no-interaction
php bin/console --env=test doctrine:fixtures:load
```

## Lancer les tests

Exécuter dans le container php :
```
php bin/phpunit
```
