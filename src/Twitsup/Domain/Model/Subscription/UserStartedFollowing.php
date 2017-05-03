<?php
declare(strict_types=1);

namespace Twitsup\Domain\Model\Subscription;

use Twitsup\Domain\Model\User\UserId;

final class UserStartedFollowing
{
    /**
     * @var SubscriptionId
     */
    private $subscriptionId;

    /**
     * @var UserId
     */
    private $followerId;

    /**
     * @var UserId
     */
    private $followeeId;

    public function __construct(SubscriptionId $subscriptionId, UserId $followerId, UserId $followeeId)
    {
        $this->subscriptionId = $subscriptionId;
        $this->followerId = $followerId;
        $this->followeeId = $followeeId;
    }

    public function subscriptionId(): SubscriptionId
    {
        return $this->subscriptionId;
    }

    public function followerId(): UserId
    {
        return $this->followerId;
    }

    public function followeeId(): UserId
    {
        return $this->followeeId;
    }
}
