<?php

namespace Twitsup\Domain\Model\User;

use EventSourcing\Aggregate\EventSourcedAggregate;
use EventSourcing\Aggregate\EventSourcingCapabilities;
use Ramsey\Uuid\UuidInterface;

final class User implements EventSourcedAggregate
{
    use EventSourcingCapabilities;

    public static function register(UuidInterface $id, string $username, string $nickname) : User
    {
        $instance = new static();
        $instance->recordThat(new UserRegistered($id, $username, $nickname));

        return $instance;
    }

    private function whenUserRegistered(UserRegistered $userRegistered)
    {
        $this->id = $userRegistered->id();
    }
}
