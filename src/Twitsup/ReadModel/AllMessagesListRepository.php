<?php

namespace Twitsup\ReadModel;

use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;

final class AllMessagesListRepository
{
    public function __construct($databasePath)
    {
        $this->repository = new Repository('messages', new Config($databasePath));
    }

    public function save(array $data)
    {
        $document = new Document($data);
        $document->setId($data['id']);
        $this->repository->store($document);
    }

    public function findAll()
    {
        $documents = $this->repository->query()->execute();

        foreach ($documents as $document) {
            yield get_object_vars($document);
        }
    }
}
