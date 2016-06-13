<?php

namespace Twitsup\Ports\Cli;

use EventSourcing\EventStore\EventStore;
use EventSourcing\Projection\EventDispatcher;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ReplayHistoryCliHandler
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(EventStore $eventStore, EventDispatcher $eventDispatcher)
    {
        $this->eventStore = $eventStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(Args $args, IO $io)
    {
        $io->writeLine('Replaying history...');

        $this->eventStore->replayHistory($this->eventDispatcher);

        $io->writeLine('<success>Done</success>');

        return 0;
    }
}
