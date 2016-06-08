<?php

namespace Simple\EventStore;

final class TransactionFailed extends \RuntimeException
{
    public static function forLowerLevelReason(\Exception $exception)
    {
        return new self(
            'Transaction failed',
            null,
            $exception
        );
    }
}
