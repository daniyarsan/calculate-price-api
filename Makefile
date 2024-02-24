#!/usr/bin/make -f
# SHELL = /bin/sh
APP_DIR=$(shell echo $$(cd . && pwd))
DC=docker-compose -f ./docker/docker-compose.yml

########## COMBINED COMMANDS
start: build up
restart: stop up
rebuild: stop start

########## STEPS
build:
	cd $(APP_DIR) && $(DC) build
up:
	docker rm -f $$(docker ps -a | grep quest | awk '{print $$1}') || echo
	cd $(APP_DIR) && $(DC) up -d --remove-orphans --force-recreate
	$(MAKE) composer
down:
	cd $(APP_DIR) && $(DC) down -v --remove-orphans
stop:
	cd $(APP_DIR) && $(DC) stop
composer:
	cd $(APP_DIR) && $(DC) exec -T php_fpm composer install && \
	wait
comp:
	cd $(APP_DIR) && $(DC) exec -T php_fpm composer


######## DB COMMANDS
console:
	$(DC) exec php_fpm php bin/console
create_db:
	$(DC) exec php_fpm php bin/console doctrine:database:create
import_db:
	$(DC) exec mysql /var/dump/dump_import.sh
	$(DC) exec php_fpm php bin/console doctrine:schema:update --force
update_db:
	$(DC) exec php_fpm php bin/console doctrine:schema:update --force

#PROCESS
####################################################################
configure:
	if ! grep -q '127.0.0.1 quest.loc' "/etc/hosts"; then \
    echo '127.0.0.1 quest.loc' | sudo tee -a /etc/hosts; \
    fi

sh:
	docker-compose exec php_fpm bash

####################################################################

clear:
	$(DC) down -v --remove-orphans
	docker container prune -f
	docker image prune -f
	docker volume prune -f
	@echo "======================="