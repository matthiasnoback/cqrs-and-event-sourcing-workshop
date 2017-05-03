#!/usr/bin/env bash

docker-compose run app /bin/bash -c "vendor/bin/phpunit && vendor/bin/behat -v"
