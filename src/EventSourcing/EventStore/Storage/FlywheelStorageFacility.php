<?php

namespace EventSourcing\EventStore\Storage;

use EventSourcing\EventStore\StorageFacility;
use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;

class FlywheelStorageFacility implements StorageFacility
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
            yield [
                'event_id' => $document->event_id,
                'event_type' => $document->event_type,
                'aggregate_type' => $document->aggregate_type,
                'aggregate_id' => $document->aggregate_id,
                'payload' => $document->payload,
                'created_at' => $document->created_at
            ];
        }
    }

    public function persistRawEvent(array $rawEventData)
    {
        $document = new Document($rawEventData);
        $document->setId($rawEventData['event_id']);

        $this->repository->store($document);
    }
}
