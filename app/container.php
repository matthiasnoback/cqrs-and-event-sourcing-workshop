<?php

use EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use EventSourcing\EventStore\EventStore;
use EventSourcing\EventStore\Storage\FlywheelStorageFacility;
use EventSourcing\EventStore\StorageFacility;
use EventSourcing\Projection\EventDispatcher;
use Twitsup\Domain\Model\MessageCreated;
use Twitsup\ReadModel\MessagesProjector;
use Twitsup\ReadModel\AllMessagesListRepository;
use Xtreamwayz\Pimple\Container;

$config = [
    'database_path' => realpath(__DIR__ . '/../var')
];

$container = new Container();

/*
 * Event store, event dispatching, etc.
 */
$container[StorageFacility::class] = function () use ($config) {
    return new FlywheelStorageFacility($config['database_path']);
};

$container[EventDispatcher::class] = function ($container) {
    $eventDispatcher = new EventDispatcher();

    $eventDispatcher->on(MessageCreated::class, function (MessageCreated $event) {
        echo spl_object_hash($event) . "\n";
    });
    $eventDispatcher->on(MessageCreated::class, $container[MessagesProjector::class]);

    return $eventDispatcher;
};

$container[EventStore::class] = function ($container) {
    return new EventStore(
        $container[StorageFacility::class],
        $container[EventDispatcher::class]
    );
};

/*
 * Domain model
 */
$container['Twitsup\Domain\Model\MessageRepository'] = function ($container) {
    return new EventSourcedAggregateRepository(
        $container[EventSourcing\EventStore\EventStore::class],
        \Twitsup\Domain\Model\Message::class
    );
};

$container[AllMessagesListRepository::class] = function () use ($config) {
    return new AllMessagesListRepository($config['database_path']);
};

/*
 * Read model
 */
$container[MessagesProjector::class] = function ($container) {
    return new MessagesProjector($container[AllMessagesListRepository::class]);
};

return $container;
