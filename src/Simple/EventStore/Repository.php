<?php

namespace Simple\EventStore;

interface Repository
{
    /**
     * @param EventSourced $aggregate
     * @throws ConcurrencyError
     * @return void
     */
    public function save(EventSourced $aggregate);

    /**
     * @param string $id
     * @return EventSourced
     */
    public function getById(string $id);
}
