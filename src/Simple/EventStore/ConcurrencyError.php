<?php

namespace Simple\EventStore;

final class ConcurrencyError extends \RuntimeException
{
    public static function forAggregate(string $aggregateType, string $aggregateId)
    {
        return new self(
            sprintf(
                'The aggregate of type "%s" with ID "%s" has been modified by another process',
                $aggregateType,
                $aggregateId
            )
        );
    }
}
