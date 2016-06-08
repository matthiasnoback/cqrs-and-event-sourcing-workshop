<?php

namespace Simple\EventStore;

abstract class EventSourcingRepository implements Repository
{
    /**
     * @var EventStore
     */
    private $eventStore;
    /**
     * @var
     */
    private $aggregateType;

    public function __construct(EventStore $eventStore, $aggregateType)
    {
        $this->eventStore = $eventStore;
        $this->aggregateType = $aggregateType;
    }

    final public function save(EventSourced $aggregate)
    {
        $this->eventStore->append($this->aggregateType, $aggregate->id(), $aggregate->popRecordedEvents());
    }
}
