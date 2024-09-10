# QoL commands

# Build our Docker Compose Image
PHONY: build
build:
	@docker-compose -f docker-compose.yml build

# Start our Docker Compose Containers
PHONY: start
start:
	@docker-compose up -d --build web

# Start app container and run our database migrationsa
PHONY: migrate
migrate:
	@docker exec app php vendor/bin/phinx migrate

# Start app container and run our database seeds
PHONY: seed
seed:
	@docker exec app php vendor/bin/phinx seed:run

# Start composer container and run command
PHONY: composer
composer:
	@docker-compose run --rm composer $(filter-out $@,$(MAKECMDGOALS))

%:
    @:
