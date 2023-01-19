Symfony REST API
==================================

# How to run #

Dependencies:

  * Run `docker-compose up -d`. This will initialise and start all the containers, then leave them running in the background.
  * Run `docker-compose exec php-fpm bash` and then run `php bin/console doctrine:migrations:migrate`to migrate database changes to your local environment.
  
## Services exposed outside your environment ##


Service|Address outside
------|---------
Webserver|[localhost:8080](http://localhost:8080)
Mailhog| [localhost:8025](http://localhost:8025) 


# Docker-compose yml files #

  * docker-compose.yml - Run to build your local environment with a local database and web server. `docker-compose up`
  * docker-compose.test.yml - Use it to run all tests suites (unit, functional tests etc). `docker-compose -f docker-compose.test.yml up`
