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
