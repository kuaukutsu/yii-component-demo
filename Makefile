PHP_VERSION ?= 8.2
USER = $$(id -u)
ENV ?= development

up:
	USER=${USER} docker-compose -f ./docker-compose.yml up -d --remove-orphans

serve:
	USER=${USER} docker-compose -f ./docker-compose.yml --profile workers up -d --remove-orphans

stop:
	docker-compose -f ./docker-compose.yml --profile workers stop

down:
	docker-compose -f ./docker-compose.yml down --remove-orphans

ps:
	docker-compose -f ./docker-compose.yml ps

cli:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -w /src/app cli sh

queue:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -w /src/app \
		-e XDEBUG_MODE=off \
 		consumer php yii queue-pool/listen -v --consumer=2

cron:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -w /src/app \
		-e XDEBUG_MODE=off \
 		cli php main/cron.php

task:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -w /src/app \
		-e XDEBUG_MODE=off \
 		cli php main/task.php

app-nginx-build:
	USER=${USER} docker-compose -f ./docker-compose.yml build nginx

app-fpm-build:
	USER=${USER} docker-compose -f ./docker-compose.yml build fpm

app-cli-build:
	USER=${USER} docker-compose -f ./docker-compose.yml build cli

app-rabbitmq-build:
	USER=${USER} docker-compose -f ./docker-compose.yml build rabbitmq

app-consumer-build:
	USER=${USER} docker-compose -f ./docker-compose.yml build consumer

consumer-worker-one-build:
	USER=${USER} docker-compose -f ./docker-compose.yml build consumer-worker-one

consumer-worker-two-build:
	USER=${USER} docker-compose -f ./docker-compose.yml build consumer-worker-two

app-worker-build: consumer-worker-one-build consumer-worker-two-build

app-database-volume-build:
	docker volume create yii_component_database

app-database-build: app-database-volume-build
	USER=${USER} docker-compose -f ./docker-compose.yml build postgresql

env-init:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -w /src/app \
		-e XDEBUG_MODE=off \
		cli php init --env=${ENV} --overwrite=y --delete=y

migrate:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -w /src/app \
		-e XDEBUG_MODE=off \
		cli php yii migrate --interactive=0

migrate-redo:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -w /src/app \
		-e XDEBUG_MODE=off \
		cli php yii migrate/redo all --interactive=0

composer:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -v "$$(pwd):/src" -w /src/app \
		-e XDEBUG_MODE=off \
		cli composer install --ignore-platform-req=ext-pcntl

composer-up:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -v "$$(pwd):/src" -w /src/app \
		-e XDEBUG_MODE=off \
		cli composer update --ignore-platform-req=ext-pcntl

composer-dump:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -v "$$(pwd):/src" -w /src/app \
		-e XDEBUG_MODE=off \
		cli composer dump-autoload --ignore-platform-req=ext-pcntl

psalm:
	docker run --init -it --rm -v "$$(pwd):/src" -w /src \
 		-e XDG_CACHE_HOME=/tmp \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./app/vendor/bin/psalm

phpunit:
	docker run --init -it --rm -v "$$(pwd):/src" -u ${USER} -w /src \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./app/vendor/bin/phpunit

phpcs:
	docker run --init -it --rm -v "$$(pwd):/src" -u ${USER} -w /src \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./app/vendor/bin/phpcs

phpcbf:
	docker run --init -it --rm -v "$$(pwd):/src" -u ${USER} -w /src \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./app/vendor/bin/phpcbf

rector:
	docker run --init -it --rm -v "$$(pwd):/src" -u ${USER} -w /src \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./app/vendor/bin/rector

waiting:
	sleep 10

# Initialization application
init: app-fpm-build app-cli-build app-consumer-build app-worker-build app-database-build composer env-init up waiting migrate

# Initialization application
redo: env-init stop waiting up migrate-redo migrate composer

rebuild: app-fpm-build app-cli-build app-nginx-build app-rabbitmq-build app-consumer-build app-worker-build app-database-build

# Code check
check:
	-make rector
	-make phpcbf
	-make psalm
