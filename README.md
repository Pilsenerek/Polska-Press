# Installation guide

## 1. Prepare mysql database and clone repository

## 2. Configure apache v-host
See example below, more: https://symfony.com/doc/current/setup/web_server_configuration.html) 

or use interior server (in console run php bin/console server:run, more: https://symfony.com/doc/current/setup.html)

    <VirtualHost polska-press.local:80>
        ServerAdmin webmaster@dummy-host.example.com
        DocumentRoot "D:/xampp_7_2/htdocs/polska-press/public"

            <Directory "D:/xampp_7_2/htdocs/polska-press/public">
        AllowOverride None

        # Apache 2.2
        Order Allow,Deny
        Allow from All
        # /Apache 2.2

        # Apache 2.4
        Require all granted
        # /Apache 2.4

        <IfModule mod_rewrite.c>
            Options -MultiViews
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ index.php [QSA,L]
        </IfModule>
        </Directory>

        ServerName polska-press.local
        ServerAlias polska-press.local
        ErrorLog "logs/polska-press.local.error.log"
        CustomLog "logs/polska-press.local.custom.log" common
    </VirtualHost>
 
## 3. Run 'composer install' to set backend libraries
If you don't have this program, install composer from https://getcomposer.org/

## 4. Run 'yarn' to set frontend libraries
If you don't have this program, install yarn from https://yarnpkg.com/

## 5. Set proper DATABASE_URL in /.env file

## 6. Generate db, tables and fixtures data
Windows: run db in main project folder

Linux: run commands from /db.bat file

## 7. Import disctricts, in console run:
    php bin/console import:districts

## 8. (Optional) Boost by Elastic Search
Elastic Search allows You to search data more widely and efficient
Your DB is - of course - involved, but only for replacing ids from ES into full entities
### 8.1 Install and run Elastic Search according to https://www.elastic.co/
### 8.2 Set application configration located in \config\optional\fos_elastica.yaml
    - set fos_elastica.enable to 1
    - change default host and port (if necessary)
### 8.3 Clear cache
    php bin/console cache:clear
You can turn on/off Elastic Search support many times, but You have to clear cache each time
### 8.4 Feed Elastic Search
    php bin/console fos:elastica:populate
### 8.5 You can confirm effect in debug bar
- Elastic Search section should appear
- Queries should be shown as well

## 9. (Optional) Run unit tests
    Run: 

    vendor\bin\simple-phpunit.bat
If you don't have xdebug you can install it or skip coverage raport by adding a --no-coverage option.
Doc: https://phpunit.readthedocs.io/en/7.4/