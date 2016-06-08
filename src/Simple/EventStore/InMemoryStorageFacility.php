<?php

namespace Simple\EventStore;

final class InMemoryStorageFacility implements StorageFacility
{
    private $events;

    public function setUp()
    {
    }

    public function loadRawEvents(string $aggregateType, string $aggregateId)
    {
        return $this->events[$aggregateType][$aggregateId] ?? [];
    }

    public function persistRawEvent(array $rawEventData) {
        $aggregateType = $rawEventData['aggregate_type'];
        $aggregateId = $rawEventData['aggregate_id'];

        $this->events[$aggregateType][$aggregateId][] = $rawEventData;
    }
}
