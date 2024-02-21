##################
# Docker compose
##################
APP_DIR=$(shell echo $$(cd . && pwd))
DC=docker-compose

start: dc_build dc_up


dc_build:
	$(DC) -f ./docker/docker-compose.yml build
dc_start:
	$(DC) -f ./docker/docker-compose.yml start
dc_stop:
	$(DC) -f ./docker/docker-compose.yml stop
dc_up:
	$(DC) -f ./docker/docker-compose.yml up -d --remove-orphans

dc_ps:
	$(DC) -f ./docker/docker-compose.yml ps
dc_logs:
	$(DC) -f ./docker/docker-compose.yml logs -f
dc_down:
	$(DC) -f ./docker/docker-compose.yml down -v --rmi=all --remove-orphans

##################
# App
##################

app_bash:
	$(DC) -f ./docker/docker-compose.yml exec -u www-data php bash
	
dc_composer:
	$(DC) -f ./docker/docker-compose.yml exec -u www-data php composer install && \
	wait

app_exec:
	$(DC) -f ./docker/docker-compose.yml exec -u www-data php
