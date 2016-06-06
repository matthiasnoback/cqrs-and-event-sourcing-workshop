<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use My\Infrastructure\UserRepositoryImpl;
use My\Model\User;
use My\Model\UserRepository;
use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\ActionEventEmitter;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\Common\Messaging\NoOpMessageConverter;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Adapter\Adapter;
use Prooph\EventStore\Adapter\Doctrine\DoctrineEventStoreAdapter;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;
use Xtreamwayz\Pimple\Container;

$config = [
    'sqlite_database_path' => realpath(__DIR__ . '/../var/') . '/cqrs_workshop.sqlite'
];

$container = new Container();
$container[Connection::class] = function ($container) use ($config) {
    $connectionParams = array(
        'driver' => 'pdo_sqlite',
        'path' => $config['sqlite_database_path']
    );
    return DriverManager::getConnection($connectionParams);
};

$container[ActionEventEmitter::class] = function () {
    return new ProophActionEventEmitter();
};

$container[Adapter::class] = function ($container) {
    return new DoctrineEventStoreAdapter(
        $container[Connection::class],
        new FQCNMessageFactory(),
        new NoOpMessageConverter(),
        new Prooph\EventStore\Adapter\PayloadSerializer\JsonPayloadSerializer()
    );
};

$container[EventStore::class] = function ($container) {
    return new EventStore($container[Adapter::class], $container[ActionEventEmitter::class]);
};

$container->extend(EventStore::class, function (EventStore $eventStore) {
    $eventStore->getActionEventEmitter()->attachListener('commit.post', function (ActionEvent $event) {
        foreach ($event->getParam('recordedEvents', []) as $streamEvent) {
            /** @var DomainEvent $streamEvent */
            echo sprintf(
                "Event with name %s was recorded. It occurred on %s UTC /// \n",
                $streamEvent->messageName(),
                $streamEvent->createdAt()->format('Y-m-d H:i:s')
            );
        }
    });

    return $eventStore;
});

$container[UserRepository::class] = function ($container) {
    return new UserRepositoryImpl(
        $container[EventStore::class],
        AggregateType::fromAggregateRootClass(User::class),
        new AggregateTranslator(),
        null,
        null,
        false
    );
};

return $container;
