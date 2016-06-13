<?php

namespace Twitsup\ReadModel;

use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;

final class UserProfileRepository
{
    public function __construct($databasePath)
    {
        $this->repository = new Repository('user_profile', new Config($databasePath));
    }

    public function save(array $data)
    {
        $document = new Document($data);
        $document->setId($data['id']);
        $this->repository->store($document);
    }

    public function getByUserId(string $userId)
    {
        $result = $this->repository->query()
            ->where('id', '==', $userId)
            ->execute()
            ->first();

        if (!$result) {
            throw new \RuntimeException('User not found');
        }

        return get_object_vars($result);
    }

    public function reset()
    {
        foreach ($this->repository->getAllFiles() as $file) {
            unlink($file);
        }
    }
}
