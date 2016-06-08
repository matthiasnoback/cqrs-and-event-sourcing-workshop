<?php

namespace Simple\EventStore;

interface StorageFacility
{
    /**
     * @return void
     */
    public function setUp();

    /**
     * @param string $aggregateType
     * @param string $aggregateId
     * @return object[]
     */
    public function loadRawEvents(string $aggregateType, string $aggregateId);

    public function persistRawEvent(array $rawEventData);

    /**
     * @param callable $callable
     * @throws TransactionFailed
     * @return void
     */
    public function transactional(callable $callable);
}
