<?php

namespace EventSourcing\Aggregate;

/**
 * Events that are supposed to be persisted using the EventStore should implement this interface.
 */
interface Event
{
    /**
     * @param string $key
     * @param mixed $value
     * @return Event
     */
    public function withMetadata(string $key, $value);

    /**
     * @param string $key
     * @throws \OutOfBoundsException
     * @return mixed
     */
    public function metadata(string $key);
}
