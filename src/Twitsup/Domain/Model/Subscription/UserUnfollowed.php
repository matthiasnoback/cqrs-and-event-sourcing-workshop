<?php
declare(strict_types=1);

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

    public function subscriptionId()
    {
        return $this->subscriptionId;
    }

    public function followerId()
    {
        return $this->followerId;
    }

    public function followeeId()
    {
        return $this->followeeId;
    }
}
