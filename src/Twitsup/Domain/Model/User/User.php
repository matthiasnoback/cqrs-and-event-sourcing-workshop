<?php
declare(strict_types=1);

namespace Twitsup\Domain\Model\User;

use Common\EventSourcing\Aggregate\EventSourcedAggregate;
use Common\EventSourcing\Aggregate\EventSourcedAggregateCapabilities;

final class User implements EventSourcedAggregate
{
    use EventSourcedAggregateCapabilities;

    public static function register(UserId $id, string $username, string $nickname): User
    {
        $instance = new static();

        $instance->recordThat(new UserRegistered($id, $username, $nickname));

        return $instance;
    }

    private function whenUserRegistered(UserRegistered $userRegistered): void
    {
        $this->id = $userRegistered->id();
    }
}
