:: if you want re-install your DB, execute this file
REM Your DB will be re-installed!
@ECHO OFF
PAUSE 
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load --no-interaction

@ECHO ON