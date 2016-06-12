<?php

namespace Twitsup\Ports\Cli;

use Twitsup\Application\SendTweet;
use Twitsup\Application\SendTweetHandler;
use Twitsup\ReadModel\UserLookupRepository;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class SendTweetCliHandler
{
    /**
     * @var SendTweetHandler
     */
    private $sendTweetHandler;
    
    /**
     * @var UserLookupRepository
     */
    private $userLookupRepository;

    public function __construct(SendTweetHandler $sendTweetHandler, UserLookupRepository $userLookupRepository)
    {
        $this->sendTweetHandler = $sendTweetHandler;
        $this->userLookupRepository = $userLookupRepository;
    }

    public function handle(Args $args, IO $io)
    {
        $tweet = new SendTweet();
        $userId = $this->userLookupRepository->getUserIdForUsername($args->getArgument('username'));
        $tweet->userId = $userId;
        $tweet->text = $args->getArgument('text');

        $io->writeLine('Sending tweet...');

        $this->sendTweetHandler->__invoke($tweet);

        $io->writeLine('<success>Done</success>');
    }
}
