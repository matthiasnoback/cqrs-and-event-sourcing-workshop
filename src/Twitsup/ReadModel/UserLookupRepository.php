<?php

namespace Twitsup\ReadModel;

use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;

final class UserLookupRepository
{
    private $repository;

    public function __construct($databasePath)
    {
        $this->repository = new Repository('user_lookup_table', new Config($databasePath));
    }

    public function save(array $data)
    {
        $document = new Document($data);
        $document->setId($data['id']);
        $this->repository->store($document);
    }

    public function getUserIdForUsername($username)
    {
        $result = $this->repository->query()
            ->andWhere('username', '==', $username)
            ->execute()
            ->first();

        if (!$result) {
            throw new \RuntimeException('User not found');
        }

        return $result->id;
    }

    public function userWithUsernameExists(string $username)
    {
        $result = $this->repository->query()
            ->andWhere('username', '==', $username)
            ->execute()
            ->first();

        return $result !== false;
    }
}
