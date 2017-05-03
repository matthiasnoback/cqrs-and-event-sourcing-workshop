<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

use Common\Persistence\Database;

final class SubscriptionLookupRepository
{
    public function save(Subscription $subscription): void
    {
        Database::persist($subscription);
    }

    public function getSubscriptionId(string $followerId, string $followeeId): string
    {
        foreach (Database::retrieveAll(Subscription::class) as $subscription) {
            /** @var Subscription $subscription */
            if ($subscription->followerId == $followerId && $subscription->followeeId == $followeeId) {
                return $subscription->id;
            }
        }

        throw new \RuntimeException('Subscription not found');
    }

    public function reset(): void
    {
        Database::deleteAll(Subscription::class);
    }
}
