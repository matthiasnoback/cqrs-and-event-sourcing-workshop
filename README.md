# CQRS workshop sandbox

## Getting started

First, clone this project and navigate to the root of the project:

    git clone git@github.com:ibuildingsnl/cqrs-workshop-code.git
    cd cqrs-workshop-code

You need:

- Vagrant
- Ansible

Once you have installed both, run:

    vagrant up

in the root of this project. This may take a while (it seems to compile Java 8 and Neo4j).

To see if the "code" works:

    vagrant ssh
    cd /vagrant
    php sandbox.php

Furthermore you might like to explode the Neo4j web interface, by going to [http://192.168.44.55:7474](http://192.168.44.55:7474) in a web browser.

## Neo4j web browser

To connect:

    :server connect

To show everything that is in the database:

    MATCH (u) RETURN (u)
