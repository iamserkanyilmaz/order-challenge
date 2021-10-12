rm -rf vendor
rm composer.lock
composer install --no-interaction;
php bin/console make:migration;
php bin/console --no-interaction doctrine:migrations:migrate