# Clean Architecture

This repo is an example of Clean Architecture within PHP Applications.

# Requirements

- [Docker](https://www.docker.com/)
- [PHP](https://www.php.com)

# Features

- Containerized with Docker.
- Dotenv.
- Controller Layer.
- Database Layer - DTOs and Repository pattern to interact with Database.
- Error Detection (for cool kids) with Whoops.
- Routing.
- Service Container.
- Service Layer.
- Templating using latte.
- Testing with PHPUnit.

# Installation

## **Development**

Follow the steps below to build and start the PHP server.

Generate environment variables.
```
cp .env.example .env
```

Build Docker image and start Docker container.
```
make build
make start
```

Install composer dependencies
```
make composer-install
```

Generate and seed database for development
```
make migrate
make seed
```

Format code and run tests
```
composer run format
composer run tests
```

# Commands
```
make build                     # Build Docker image
make start                     # Start Docker image
make migrate                   # Migrate migrations
make seed                      # Run seeds - DEVELOPMENT ONLY
make composer-install          # Run composer install inside container
make composer-update           # Run composer update inside container

composer run format            # Run formatter (Make changes)
composer run lint              # Run linter (Report style violations)
composer run tests             # Run tests
```

# Folder Structure
```
app                             # Application logic such as Core, Http, Repositories and utilities
cache                           # Template caching.
config                          # Application configurations
database                        # Database migrations / seeds
docker                          # Docker files
public                          # Public access files
routes                          # Route definitions
tests                          # PHPUnit Tests
vendor                          # Composer Vendor files
views                           # Views
```

# Contributing

Review the [CONTRIBUTING.md](CONTRIBUTING.md) on how to contribute.




