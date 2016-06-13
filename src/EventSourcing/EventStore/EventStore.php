<?php

namespace EventSourcing\EventStore;

use EventSourcing\Projection\EventDispatcher;
use Ramsey\Uuid\Uuid;

final class EventStore
{
    /**
     * @var StorageFacility
     */
    private $storageFacility;
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(StorageFacility $storageFacility, EventDispatcher $eventDispatcher)
    {
        $this->storageFacility = $storageFacility;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function append(string $aggregateType, string $aggregateId, array $events)
    {
        foreach ($events as $event) {
            $id = (string)Uuid::uuid4();
            $eventType = get_class($event);
            $payload = $this->extractPayload($event);
            $createdAt = $event->metadata('created_at')->format('Y-m-d\TH:i:s.u');
            $this->storageFacility->persistRawEvent(
                [
                    'event_type' => $eventType,
                    'event_id' => $id,
                    'payload' => $payload,
                    'aggregate_type' => $aggregateType,
                    'aggregate_id' => $aggregateId,
                    'created_at' => $createdAt
                ]
            );

            $this->eventDispatcher->dispatch($event);
        }
    }

    public function loadEvents($aggregateType, $aggregateId)
    {
        foreach ($this->storageFacility->loadRawEvents($aggregateType, $aggregateId) as $rawEvent) {
            yield $this->restoreEvent($rawEvent);
        }
    }

    /**
     * Load all historic events and dispatch them to the provided EventDispatcher.
     *
     * @param EventDispatcher $eventDispatcher
     */
    public function replayHistory(EventDispatcher $eventDispatcher)
    {
        $allEvents = $this->storageFacility->loadAllRawEvents();
        foreach ($allEvents as $rawEvent) {
            $eventDispatcher->dispatch($this->restoreEvent($rawEvent));
        }
    }

    public function reconstitute($aggregateType, $aggregateId)
    {
        return call_user_func([$aggregateType, 'reconstitute'], $this->loadEvents($aggregateType, $aggregateId));
    }

    private function extractPayload($event)
    {
        return serialize($event);
    }

    private function restoreEvent(array $rawEvent)
    {
        return unserialize($rawEvent['payload']);
    }
}
