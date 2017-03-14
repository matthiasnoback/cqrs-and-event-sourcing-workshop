# Code and assignments for the "CQRS & Event Sourcing" workshop module

## Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting started

- Clone this repository and `cd` into it.
- Run `docker-compose pull`.
- Run `bin/composer.sh install --prefer-dist` to install the project's dependencies.
- [Follow the instructions](https://github.com/matthiasnoback/php-workshop-tools/blob/master/README.md) for setting environment variables and configuring PhpStorm for debugging.

## Running development tools

- Run `bin/composer.sh` to use Composer (e.g. `bin/composer.sh require symfony/var-dumper`).
- Run `bin/run_tests.sh` to run the tests.
- Run `bin/sandbox.sh` to run the `sandbox.php` script.
- Run `bin/twitsup.sh` to run the `twitsup` CLI application.

## Neo4j web browser

Open [http://localhost:7474/](http://localhost:7474/) in a browser to use Neo4j's graphical user interface. You can use the console to run [Cypher queries](https://neo4j.com/developer/cypher-query-language/). The results will be presented in a nice graph.  

As an example, to show everything that is in the database, run:

    MATCH (u) RETURN (u)
