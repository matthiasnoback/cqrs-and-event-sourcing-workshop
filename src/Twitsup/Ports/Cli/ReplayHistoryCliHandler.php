<?php
declare(strict_types=1);

namespace Twitsup\Ports\Cli;

use Common\EventSourcing\EventStore\EventStore;
use Common\EventDispatcher\EventDispatcher;
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
    /**
     * @var array
     */
    private $readModelRepositories;

    public function __construct(EventStore $eventStore, EventDispatcher $eventDispatcher, array $readModelRepositories)
    {
        $this->eventStore = $eventStore;
        $this->eventDispatcher = $eventDispatcher;
        $this->readModelRepositories = $readModelRepositories;
    }

    public function handle(Args $args, IO $io)
    {
        $io->writeLine('Reset read models...');

        foreach ($this->readModelRepositories as $readModelRepository) {
            $readModelRepository->reset();
        }

        $io->writeLine('Replay history...');

        $this->eventStore->replayHistory($this->eventDispatcher);

        $io->writeLine('<success>Done</success>');

        return 0;
    }
}
