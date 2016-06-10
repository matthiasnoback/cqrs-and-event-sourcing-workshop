<?php

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

    public function __invoke(UserStartedFollowing $event)
    {
        $this->repository->save([
            'id' => (string)$event->subscriptionId(),
            'followerId' => (string)$event->followerId(),
            'followeeId' => (string)$event->followeeId()
        ]);
    }
}
