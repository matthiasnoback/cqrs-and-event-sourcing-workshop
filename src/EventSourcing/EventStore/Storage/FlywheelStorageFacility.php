<?php

namespace EventSourcing\EventStore\Storage;

use EventSourcing\Aggregate\Event;
use EventSourcing\EventStore\StorageFacility;
use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;

final class FlywheelStorageFacility implements StorageFacility
{
    /**
     * @var Repository
     */
    private $repository;

    public function __construct(string $databaseDirectory)
    {
        $config = new Config($databaseDirectory);
        $this->repository = new Repository('events', $config);
    }

    public function setUp()
    {
    }

    public function loadRawEvents(string $aggregateType, string $aggregateId)
    {
        $documents = $this->repository->query()
            ->andWhere('aggregate_type', '==', $aggregateType)
            ->andWhere('aggregate_id', '==', $aggregateId)
            ->orderBy('created_at ASC')
            ->execute()
        ;

        foreach ($documents as $document) {
            yield get_object_vars($document);
        }
    }

    public function loadAllRawEvents()
    {
        $documents = $this->repository->query()
            ->orderBy('created_at ASC')
            ->execute()
        ;

        foreach ($documents as $document) {
            yield get_object_vars($document);
        }
    }

    public function persistRawEvent(array $rawEventData)
    {
        $document = new Document($rawEventData);
        $document->setId($rawEventData['event_id']);

        $this->repository->store($document);
    }

    public function reset()
    {
        foreach ($this->repository->getAllFiles() as $file) {
            unlink($file);
        }
    }
}
