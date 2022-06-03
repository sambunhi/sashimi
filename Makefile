.DEFAULT_GOAL:=help

COMPOSE_PREFIX_CMD := DOCKER_BUILDKIT=1 COMPOSE_DOCKER_CLI_BUILD=1

COMMAND ?= /bin/sh

IN_PHP81_COMPOSER := docker run --rm \
	-u "$(shell id -u):$(shell id -g)" \
	-v $(shell pwd):/var/www/html \
	-w /var/www/html \
	laravelsail/php81-composer:latest

# --------------------------

.PHONY: deploy
deploy:			## Start using Prod Image in Prod Mode
	${COMPOSE_PREFIX_CMD} docker-compose -f docker-compose.prod.yml up --build -d

vendor:
	${IN_PHP81_COMPOSER} composer install --ignore-platform-reqs

.env:
	${IN_PHP81_COMPOSER} /bin/bash -c "composer run-script post-root-package-install && composer run-script post-create-project-cmd"

.PHONY: up
up:				## Start service
	@echo "Starting Application \n (note: Web container will wait App container to start before starting)"
	${COMPOSE_PREFIX_CMD} docker-compose up -d

.PHONY: build-up
build-up:       ## Start service, rebuild if necessary
	${COMPOSE_PREFIX_CMD} docker-compose up --build -d

.PHONY: build
build: vendor .env ## Install dependencies and build the image
	${COMPOSE_PREFIX_CMD} docker-compose build

.PHONY: down
down:			## Down service and do clean up
	${COMPOSE_PREFIX_CMD} docker-compose down

.PHONY: start
start:			## Start Container
	${COMPOSE_PREFIX_CMD} docker-compose start

.PHONY: stop
stop:			## Stop Container
	${COMPOSE_PREFIX_CMD} docker-compose stop

.PHONY: logs
logs:			## Tail container logs with -n 1000
	@${COMPOSE_PREFIX_CMD} docker-compose logs --follow --tail=100

.PHONY: images
images:			## Show Image created by this Makefile (or Docker-compose in docker)
	@${COMPOSE_PREFIX_CMD} docker-compose images

.PHONY: ps
ps:			## Show Containers Running
	@${COMPOSE_PREFIX_CMD} docker-compose ps

.PHONY: command
command:	  ## Execute command ( make command COMMAND=<command> )
	@${COMPOSE_PREFIX_CMD} docker-compose run --rm app ${COMMAND}

.PHONY: command-root
command-root:	 ## Execute command as root ( make command-root COMMAND=<command> )
	@${COMPOSE_PREFIX_CMD} docker-compose run --rm -u root app ${COMMAND}

.PHONY: shell-root
shell-root:			## Enter container shell as root
	@${COMPOSE_PREFIX_CMD} docker-compose exec -u root app /bin/sh

.PHONY: shell
shell:			## Enter container shell
	@${COMPOSE_PREFIX_CMD} docker-compose exec app /bin/sh

.PHONY: restart
restart:		## Restart container
	@${COMPOSE_PREFIX_CMD} docker-compose restart

.PHONY: rm
rm:				## Remove current container
	@${COMPOSE_PREFIX_CMD} docker-compose rm -f

.PHONY: tinker
tinker:			## Start a new Laravel Tinker session
	@${COMPOSE_PREFIX_CMD} docker-compose exec app php artisan tinker

.PHONY: help
help:       	## Show this help.
	@echo "\n\nMake Application Docker Images and Containers using Docker-Compose files"
	@echo "Make sure you are using \033[0;32mDocker Version >= 20.1\033[0m & \033[0;32mDocker-Compose >= 1.27\033[0m "
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m ENV=<prod|dev> (default: dev)\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)
