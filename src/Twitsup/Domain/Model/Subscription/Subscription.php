<?php

namespace Twitsup\Domain\Model\Subscription;

use EventSourcing\Aggregate\EventSourcedAggregate;
use EventSourcing\Aggregate\EventSourcingCapabilities;
use Ramsey\Uuid\UuidInterface;

final class Subscription implements EventSourcedAggregate
{
    use EventSourcingCapabilities;

    const STATUS_FOLLOWING = 'following';

    private $status;
    private $followerId;
    private $followeeId;

    /**
     * @param UuidInterface $subscriptionId
     * @param UuidInterface $followerId
     * @param UuidInterface $followeeId
     * @return Subscription
     */
    public static function startFollowing(UuidInterface $subscriptionId, UuidInterface $followerId, UuidInterface $followeeId)
    {
        if ($followerId->equals($followeeId)) {
            throw new AUserCanNotFollowThemselves();
        }

        $instance = new static();
        $instance->recordThat(new UserStartedFollowing($subscriptionId, $followerId, $followeeId));

        return $instance;
    }

    public function follow()
    {
        $this->recordThat(new UserFollowed($this->id, $this->followerId, $this->followeeId));
    }

    private function whenUserStartedFollowing(UserStartedFollowing $event)
    {
        $this->id = $event->subscriptionId();
        $this->followerId = $event->followerId();
        $this->followeeId = $event->followeeId();
        $this->status = self::STATUS_FOLLOWING;
    }

    private function whenUserFollowed(UserFollowed $event)
    {
        $this->status = self::STATUS_FOLLOWING;
    }
}
