<?php

namespace Twitsup\ReadModel;

use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;

final class SubscriptionLookupRepository
{
    public function __construct($databasePath)
    {
        $this->repository = new Repository('subscription_lookup', new Config($databasePath));
    }

    public function save(array $data)
    {
        $document = new Document($data);
        $document->setId($data['id']);
        $this->repository->store($document);
    }

    public function getSubscriptionId(string $followerId, string $followeeId)
    {
        $result = $this->repository->query()
            ->andWhere('followerId', '==', $followerId)
            ->andWhere('followeeId', '==', $followeeId)
            ->execute()
            ->first();

        if (!$result) {
            throw new \RuntimeException('Subscription not found');
        }

        return $result->id;
    }
}
