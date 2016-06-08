<?php

namespace Simple\EventStore;

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
