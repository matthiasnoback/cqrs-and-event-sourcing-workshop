<?php
declare(strict_types=1);

namespace Twitsup\Domain\Model\Subscription;

use Common\EventSourcing\Aggregate\EventSourcedAggregate;
use Common\EventSourcing\Aggregate\EventSourcedAggregateCapabilities;
use Twitsup\Domain\Model\User\UserId;

final class Subscription implements EventSourcedAggregate
{
    use EventSourcedAggregateCapabilities;

    const STATUS_FOLLOWING = 'following';

    /**
     * @var SubscriptionId
     */
    private $id;

    /**
     * @var string
     */
    private $status;

    /**
     * @var UserId
     */
    private $followerId;

    /**
     * @var UserId
     */
    private $followeeId;

    public static function startFollowing(SubscriptionId $subscriptionId, UserId $followerId, UserId $followeeId): Subscription
    {
        if ($followerId->equals($followeeId)) {
            throw new AUserCanNotFollowThemselves();
        }

        $instance = new static();
        $instance->recordThat(new UserStartedFollowing($subscriptionId, $followerId, $followeeId));

        return $instance;
    }

    private function whenUserStartedFollowing(UserStartedFollowing $event): void
    {
        $this->id = $event->subscriptionId();
        $this->followerId = $event->followerId();
        $this->followeeId = $event->followeeId();
        $this->status = self::STATUS_FOLLOWING;
    }

    public function follow(): void
    {
        $this->recordThat(new UserFollowed($this->id, $this->followerId, $this->followeeId));
    }

    private function whenUserFollowed(UserFollowed $event): void
    {
        $this->status = self::STATUS_FOLLOWING;
    }
}
