<?php

namespace Twitsup\Application;

use EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use Ramsey\Uuid\Uuid;
use Twitsup\Domain\Model\Subscription\Subscription;
use Twitsup\ReadModel\SubscriptionLookupRepository;
use Twitsup\ReadModel\UserLookupRepository;

final class UnfollowUserHandler
{
    /**
     * @var UserLookupRepository
     */
    private $userLookupTableRepository;
    /**
     * @var SubscriptionLookupRepository
     */
    private $subscriptionLookupRepository;
    /**
     * @var EventSourcedAggregateRepository
     */
    private $subscriptionRepository;

    public function __construct(
        UserLookupRepository $userLookupTableRepository,
        SubscriptionLookupRepository $subscriptionLookupRepository,
        EventSourcedAggregateRepository $subscriptionRepository
    ) {
        $this->userLookupTableRepository = $userLookupTableRepository;
        $this->subscriptionLookupRepository = $subscriptionLookupRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function __invoke(UnfollowUser $command)
    {
        $followerId = $this->userLookupTableRepository->getUserIdForUsername($command->followerUsername);
        $followeeId = $this->userLookupTableRepository->getUserIdForUsername($command->followeeUsername);

        try {
            $subscriptionId = $this->subscriptionLookupRepository->getSubscriptionId($followerId, $followeeId);
            /** @var Subscription $subscription */
            $subscription = $this->subscriptionRepository->getById($subscriptionId);
            $subscription->unfollow();
            $this->subscriptionRepository->save($subscription);
        } catch (\RuntimeException $exception) {
            // There is no subscription for these users, so there's nothing to do.
        }
    }
}
