#!/bin/sh
set -e

echo ">>> Install composer dependencies"
composer install --no-interaction

echo ">>> Création des bases de données"
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:database:create --env=test --if-not-exists

echo ">>> Lancement des migrations"
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:migrations:migrate --env=test --no-interaction

echo ">>> Chargement des fixtures"
php bin/console doctrine:fixtures:load --no-interaction
php bin/console doctrine:fixtures:load --env=test --no-interaction

exec "$@"
