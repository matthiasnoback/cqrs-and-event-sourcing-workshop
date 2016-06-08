<?php

namespace EventSourcing\EventStore;

use Ramsey\Uuid\Uuid;

final class EventStore
{
    /**
     * @var StorageFacility
     */
    private $storageFacility;

    public function __construct(StorageFacility $storageFacility)
    {
        $this->storageFacility = $storageFacility;
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
        }
    }

    public function loadEvents($aggregateType, $aggregateId)
    {
        foreach ($this->storageFacility->loadRawEvents($aggregateType, $aggregateId) as $rawEvent) {
            yield $this->restoreEvent($rawEvent);
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
