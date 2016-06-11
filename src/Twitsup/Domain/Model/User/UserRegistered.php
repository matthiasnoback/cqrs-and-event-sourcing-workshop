<?php

namespace Twitsup\Domain\Model\User;

use EventSourcing\Aggregate\Event;
use EventSourcing\Aggregate\EventCapabilities;
use Ramsey\Uuid\UuidInterface;

final class UserRegistered implements Event
{
    use EventCapabilities;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $nickname;

    public function __construct(UuidInterface $id, string $username, string $nickname)
    {
        $this->id = $id;
        $this->username = $username;
        $this->nickname = $nickname;
    }

    public function id()
    {
        return $this->id;
    }

    public function username()
    {
        return $this->username;
    }

    public function nickname()
    {
        return $this->nickname;
    }
}
