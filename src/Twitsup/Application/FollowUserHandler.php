<?php
declare(strict_types=1);

namespace Twitsup\Application;

use Common\EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use Ramsey\Uuid\Uuid;
use Twitsup\Domain\Model\Subscription\Subscription;
use Twitsup\Domain\Model\Subscription\SubscriptionId;
use Twitsup\Domain\Model\User\UserId;
use Twitsup\ReadModel\SubscriptionLookupRepository;
use Twitsup\ReadModel\UserLookupRepository;

final class FollowUserHandler
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

    public function __invoke(FollowUser $command): void
    {
        $followerId = $this->userLookupTableRepository->getUserIdForUsername($command->followerUsername);
        $followeeId = $this->userLookupTableRepository->getUserIdForUsername($command->followeeUsername);

        try {
            $subscriptionId = $this->subscriptionLookupRepository->getSubscriptionId($followerId, $followeeId);
            $subscription = $this->subscriptionRepository->getById($subscriptionId);
            $subscription->follow();
        } catch (\RuntimeException $exception) {
            $subscription = Subscription::startFollowing(
                SubscriptionId::fromString((string)Uuid::uuid4()),
                UserId::fromString($followerId),
                UserId::fromString($followeeId)
            );
        }

        $this->subscriptionRepository->save($subscription);
    }
}
