<?php

namespace Twitsup\Ports\Cli;

use Ramsey\Uuid\Uuid;
use Twitsup\Application\RegisterUser;
use Twitsup\Application\RegisterUserHandler;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class RegisterUserCliHandler
{
    /**
     * @var RegisterUserHandler
     */
    private $registerUserHandler;

    public function __construct(RegisterUserHandler $registerUserHandler)
    {
        $this->registerUserHandler = $registerUserHandler;
    }

    public function handle(Args $args, IO $io)
    {
        $registerUser = new RegisterUser();
        $registerUser->id = (string)Uuid::uuid4();
        $registerUser->username = $args->getArgument('username');
        $registerUser->nickname = $args->getArgument('nickname');

        $io->writeLine(sprintf('Registering user <data>%s</data>', $registerUser->id));

        $this->registerUserHandler->__invoke($registerUser);

        $io->writeLine('<success>Done</success>');
    }
}
