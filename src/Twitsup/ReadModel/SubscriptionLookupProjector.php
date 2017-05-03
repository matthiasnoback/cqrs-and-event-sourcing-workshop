<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

use Twitsup\Domain\Model\Subscription\UserStartedFollowing;

class SubscriptionLookupProjector
{
    /**
     * @var UserLookupRepository
     */
    private $repository;

    public function __construct(SubscriptionLookupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UserStartedFollowing $event): void
    {
        $subscription = new Subscription();
        $subscription->id = (string)$event->subscriptionId();
        $subscription->followerId = (string)$event->followerId();
        $subscription->followeeId = (string)$event->followeeId();

        $this->repository->save($subscription);
    }
}
