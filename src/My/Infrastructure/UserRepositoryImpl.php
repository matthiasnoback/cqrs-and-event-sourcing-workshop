<?php

namespace My\Infrastructure;

use My\Model\User;
use My\Model\UserRepository;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;
use Rhumsaa\Uuid\Uuid;

class UserRepositoryImpl extends AggregateRepository implements UserRepository
{
    public function __construct(EventStore $eventStore)
    {
        //We inject a Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator that can handle our AggregateRoots
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass('My\Model\User'),
            new AggregateTranslator(),
            null, //We don't use a snapshot store in the example
            null, //Also a custom stream name is not required
            true //But we enable the "one-stream-per-aggregate" mode
        );
    }

    /**
     * @param User $user
     */
    public function add(User $user)
    {
        $this->addAggregateRoot($user);
    }

    /**
     * @param Uuid $uuid
     * @return User
     */
    public function get(Uuid $uuid)
    {
        return $this->getAggregateRoot($uuid->toString());
    }
}
