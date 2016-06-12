<?php

namespace Twitsup\Ports\Cli;

use Twitsup\Application\FollowUser;
use Twitsup\Application\FollowUserHandler;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class FollowUserCliHandler
{
    /**
     * @var FollowUserHandler
     */
    private $followUserHandler;

    public function __construct(FollowUserHandler $followUserHandler)
    {
        $this->followUserHandler = $followUserHandler;
    }

    public function handle(Args $args, IO $io)
    {
        $followUser = new FollowUser();
        $followUser->followerUsername = $args->getArgument('follower');
        $followUser->followeeUsername = $args->getArgument('followee');

        $io->writeLine('Follow user...');
        $this->followUserHandler->__invoke($followUser);
        $io->writeLine('<success>Done</success>');
    }
}
