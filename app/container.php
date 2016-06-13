<?php

use EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use EventSourcing\EventStore\EventStore;
use EventSourcing\EventStore\Storage\FlywheelStorageFacility;
use EventSourcing\EventStore\StorageFacility;
use EventSourcing\Projection\EventDispatcher;
use GraphAware\Neo4j\Client\Client;
use GraphAware\Neo4j\Client\ClientBuilder;
use Twitsup\Application\FollowUserHandler;
use Twitsup\Application\RegisterUserHandler;
use Twitsup\Application\SendTweetHandler;
use Twitsup\Domain\Model\Subscription\Subscription;
use Twitsup\Domain\Model\Subscription\UserFollowed;
use Twitsup\Domain\Model\Subscription\UserStartedFollowing;
use Twitsup\Domain\Model\Subscription\UserUnfollowed;
use Twitsup\Domain\Model\Tweet\Tweeted;
use Twitsup\Domain\Model\User\UserRegistered;
use Twitsup\Ports\Cli\FollowUserCliHandler;
use Twitsup\Ports\Cli\RegisterUserCliHandler;
use Twitsup\Ports\Cli\ReplayHistoryCliHandler;
use Twitsup\Ports\Cli\SendTweetCliHandler;
use Twitsup\Ports\Cli\ShowTimelineCliHandler;
use Twitsup\ReadModel\FollowersProjector;
use Twitsup\ReadModel\FollowersRepository;
use Twitsup\ReadModel\TimelineProjector;
use Twitsup\ReadModel\SubscriptionLookupProjector;
use Twitsup\ReadModel\SubscriptionLookupRepository;
use Twitsup\ReadModel\TimelineRepository;
use Twitsup\ReadModel\UserLookupProjector;
use Twitsup\ReadModel\UserLookupRepository;
use Twitsup\ReadModel\UserProfileProjector;
use Twitsup\ReadModel\UserProfileRepository;
use Xtreamwayz\Pimple\Container;

$config = [
    'database_path' => realpath(__DIR__ . '/../var'),
    'neo4j_password' => 'neo4j'
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

    $eventDispatcher->on(UserRegistered::class, $container[UserLookupProjector::class]);

    $eventDispatcher->on(UserRegistered::class, $container[UserProfileProjector::class]);

    $eventDispatcher->on(UserStartedFollowing::class, $container[SubscriptionLookupProjector::class]);

    $followersProjector = $container[FollowersProjector::class];
    $eventDispatcher->on(UserStartedFollowing::class, [$followersProjector, 'onUserStartedFollowing']);
    $eventDispatcher->on(UserFollowed::class, [$followersProjector, 'onUserFollowed']);
    $eventDispatcher->on(UserUnfollowed::class, [$followersProjector, 'onUserUnfollowed']);

    $eventDispatcher->on(Tweeted::class, $container[TimelineProjector::class]);

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
$container['Twitsup\Domain\Model\TweetRepository'] = function ($container) {
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
$container['Twitsup\Domain\Model\SubscriptionRepository'] = function ($container) {
    return new EventSourcedAggregateRepository(
        $container[EventSourcing\EventStore\EventStore::class],
        Subscription::class
    );
};

/*
 * Read model
 */
$container[Client::class] = function () use ($config) {
    return ClientBuilder::create()
        ->addConnection('default', sprintf(
            'http://neo4j:%s@localhost:7474',
            $config['neo4j_password']
        ))
        ->build();
};

$container[UserLookupRepository::class] = function () use ($config) {
    return new UserLookupRepository($config['database_path']);
};
$container[UserLookupProjector::class] = function ($container) {
    return new UserLookupProjector($container[UserLookupRepository::class]);
};

$container[UserProfileRepository::class] = function () use ($config) {
    return new UserProfileRepository($config['database_path']);
};
$container[UserProfileProjector::class] = function ($container) {
    return new UserProfileProjector($container[UserProfileRepository::class]);
};

$container[SubscriptionLookupRepository::class] = function () use ($config) {
    return new SubscriptionLookupRepository($config['database_path']);
};
$container[SubscriptionLookupProjector::class] = function ($container) {
    return new SubscriptionLookupProjector($container[SubscriptionLookupRepository::class]);
};

$container[FollowersRepository::class] = function ($container) {
    return new FollowersRepository($container[GraphAware\Neo4j\Client\Client::class]);
};
$container[FollowersProjector::class] = function ($container) {
    return new FollowersProjector($container[FollowersRepository::class]);
};

$container[TimelineRepository::class] = function () use ($config) {
    return new TimelineRepository($config['database_path']);
};
$container[TimelineProjector::class] = function ($container) {
    return new TimelineProjector(
        $container[FollowersRepository::class],
        $container[UserProfileRepository::class],
        $container[TimelineRepository::class]
    );
};

/*
 * Application services
 */
$container[RegisterUserHandler::class] = function ($container) {
    return new RegisterUserHandler(
        $container['Twitsup\Domain\Model\UserRepository'],
        $container[UserLookupRepository::class]
    );
};
$container[SendTweetHandler::class] = function ($container) {
    return new SendTweetHandler($container['Twitsup\Domain\Model\TweetRepository']);
};
$container[FollowUserHandler::class] = function ($container) {
    return new FollowUserHandler(
        $container[UserLookupRepository::class],
        $container[SubscriptionLookupRepository::class],
        $container['Twitsup\Domain\Model\SubscriptionRepository']
    );
};

/*
 * Port: CLI
 */
$container[SendTweetCliHandler::class] = function ($container) {
    return new SendTweetCliHandler($container[SendTweetHandler::class], $container[UserLookupRepository::class]);
};
$container[RegisterUserCliHandler::class] = function ($container) {
    return new RegisterUserCliHandler($container[RegisterUserHandler::class]);
};
$container[FollowUserCliHandler::class] = function ($container) {
    return new FollowUserCliHandler($container[FollowUserHandler::class]);
};
$container[ShowTimelineCliHandler::class] = function ($container) {
    return new ShowTimelineCliHandler($container[UserLookupRepository::class], $container[TimelineRepository::class]);
};
$container[ReplayHistoryCliHandler::class] = function ($container) {
    return new ReplayHistoryCliHandler(
        $container[EventStore::class],
        $container[EventDispatcher::class]
    );
};

return $container;
