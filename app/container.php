<?php

use EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use EventSourcing\EventStore\EventStore;
use EventSourcing\EventStore\Storage\FlywheelStorageFacility;
use EventSourcing\EventStore\StorageFacility;
use Xtreamwayz\Pimple\Container;

$config = [
    'database_path' => realpath(__DIR__ . '/../var')
];

$container = new Container();

$container[StorageFacility::class] = function () use ($config) {
    return new FlywheelStorageFacility($config['database_path']);
};

$container[EventStore::class] = function ($container) {
    return new EventSourcing\EventStore\EventStore($container[StorageFacility::class]);
};

$container['Twitter\Domain\Model\MessageRepository'] = function ($container) {
    return new EventSourcedAggregateRepository(
        $container[EventSourcing\EventStore\EventStore::class],
        \Twitter\Domain\Model\Message::class
    );
};

return $container;
