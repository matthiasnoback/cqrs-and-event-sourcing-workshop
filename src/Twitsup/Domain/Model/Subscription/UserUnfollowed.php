<?php

namespace Twitsup\Domain\Model\Subscription;

use EventSourcing\Aggregate\Event;
use EventSourcing\Aggregate\EventCapabilities;
use Ramsey\Uuid\UuidInterface;

final class UserUnfollowed implements Event
{
    use EventCapabilities;

    /**
     * @var UuidInterface
     */
    private $subscriptionId;

    /**
     * @var UuidInterface
     */
    private $followerId;

    /**
     * @var UuidInterface
     */
    private $followeeId;

    public function __construct(UuidInterface $subscriptionId, UuidInterface $followerId, UuidInterface $followeeId)
    {
        $this->subscriptionId = $subscriptionId;
        $this->followerId = $followerId;
        $this->followeeId = $followeeId;
    }

    /**
     * @return UuidInterface
     */
    public function subscriptionId()
    {
        return $this->subscriptionId;
    }

    /**
     * @return UuidInterface
     */
    public function followerId()
    {
        return $this->followerId;
    }

    /**
     * @return UuidInterface
     */
    public function followeeId()
    {
        return $this->followeeId;
    }
}
