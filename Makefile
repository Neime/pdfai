# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php-fpm
COMPOSER = $(PHP_CONT) composer

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
PHPUNIT  = $(PHP_CONT) vendor/bin/phpunit
PHPCSFIXER  = $(PHP_CONT) vendor/bin/php-cs-fixer

build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) sh

composer: ## Run composer, pass the parameter "c=" to run a given command
	@$(eval c ?=)
	@$(COMPOSER) $(c)

unit: ## List all phpunit commands or pass the parameter "c=" to run a given command
	@$(eval c ?=)
	@$(PHPUNIT) $(c)

fixer: ## Php cs fixer
	@$(PHPCSFIXER) --verbose fix src/