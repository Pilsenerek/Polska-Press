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