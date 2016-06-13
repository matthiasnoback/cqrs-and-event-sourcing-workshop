<?php

namespace EventSourcing\EventStore;

use EventSourcing\Aggregate\Event;

interface StorageFacility
{
    /**
     * @return void
     */
    public function setUp();

    /**
     * @param string $aggregateType
     * @param string $aggregateId
     * @return \Iterator|Event[]
     */
    public function loadRawEvents(string $aggregateType, string $aggregateId);

    /**
     * @return \Iterator|Event[]
     */
    public function loadAllRawEvents();

    public function persistRawEvent(array $rawEventData);
}
