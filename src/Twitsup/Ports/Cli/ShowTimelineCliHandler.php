<?php

namespace Twitsup\Ports\Cli;

use Twitsup\ReadModel\TimelineRepository;
use Twitsup\ReadModel\UserLookupRepository;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ShowTimelineCliHandler
{
    /**
     * @var UserLookupRepository
     */
    private $userLookupRepository;

    /**
     * @var TimelineRepository
     */
    private $timelineRepository;

    public function __construct(UserLookupRepository $userLookupRepository, TimelineRepository $timelineRepository)
    {
        $this->timelineRepository = $timelineRepository;
        $this->userLookupRepository = $userLookupRepository;
    }

    public function handle(Args $args, IO $io)
    {
        $username = $args->getArgument('username');

        $userId = $this->userLookupRepository->getUserIdForUsername($username);

        $io->writeLine(sprintf('Timeline for user <data>%s</data>', $username));

        $timeline = $this->timelineRepository->timelineFor($userId);

        $io->writeRaw($timeline);
    }
}
