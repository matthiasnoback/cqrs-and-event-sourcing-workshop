<?php

use EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use EventSourcing\EventStore\EventStore;
use EventSourcing\EventStore\Storage\FlywheelStorageFacility;
use EventSourcing\EventStore\StorageFacility;
use EventSourcing\Projection\EventDispatcher;
use Twitsup\Domain\Model\Tweet\Tweeted;
use Twitsup\Domain\Model\User\UserRegistered;
use Twitsup\ReadModel\TweetsProjector;
use Twitsup\ReadModel\AllTweetsListRepository;
use Twitsup\ReadModel\UserLookupTableProjector;
use Twitsup\ReadModel\UserLookupTableRepository;
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

    $eventDispatcher->on(Tweeted::class, function (Tweeted $event) {
        echo spl_object_hash($event) . "\n";
    });
    $eventDispatcher->on(Tweeted::class, $container[TweetsProjector::class]);
    $eventDispatcher->on(UserRegistered::class, $container[UserLookupTableProjector::class]);

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
        \Twitsup\Domain\Model\Tweet\Tweet::class
    );
};
$container['Twitsup\Domain\Model\UserRepository'] = function ($container) {
    return new EventSourcedAggregateRepository(
        $container[EventSourcing\EventStore\EventStore::class],
        \Twitsup\Domain\Model\User\User::class
    );
};

/*
 * Read model
 */
$container[AllTweetsListRepository::class] = function () use ($config) {
    return new AllTweetsListRepository($config['database_path']);
};

$container[TweetsProjector::class] = function ($container) {
    return new TweetsProjector($container[AllTweetsListRepository::class]);
};

$container[UserLookupTableRepository::class] = function () use ($config) {
    return new UserLookupTableRepository($config['database_path']);
};

$container[UserLookupTableProjector::class] = function ($container) {
    return new UserLookupTableProjector($container[UserLookupTableRepository::class]);
};

return $container;
