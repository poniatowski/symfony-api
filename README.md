Symfony REST API
==================================

# How to run #

Dependencies:

  * Run `docker-compose up -d`. This will initialise and start all the containers, then leave them running in the background.
  * Run `docker-compose exec php-fpm bash` and then `composer install` to install all relevant dependencies.
  * Run `php bin/console doctrine:migrations:migrate`to migrate database change to your local environment.
  
## Services exposed outside your environment ##


Service|Address outside containers
------|---------
Webserver|[localhost:8080](http://localhost:8080)
Mailhog| 8025 