<?php

namespace EventSourcing\Aggregate\Repository;

use Assert\Assertion;
use EventSourcing\Aggregate\EventSourcedAggregate;
use EventSourcing\EventStore\EventStore;

final class EventSourcedAggregateRepository
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var string
     */
    private $aggregateType;

    public function __construct(EventStore $eventStore, $aggregateType)
    {
        $this->eventStore = $eventStore;
        $this->aggregateType = $aggregateType;
    }

    final public function save(EventSourcedAggregate $aggregate)
    {
        Assertion::same(get_class($aggregate), $this->aggregateType);

        $this->eventStore->append($this->aggregateType, $aggregate->id(), $aggregate->popRecordedEvents());
    }

    final public function getById(string $id)
    {
        return $this->eventStore->reconstitute($this->aggregateType, $id);
    }
}
