<?php

namespace Twitsup\Application;

use EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use Ramsey\Uuid\Uuid;
use Twitsup\Domain\Model\User\User;

final class RegisterUserHandler
{
    /**
     * @var EventSourcedAggregateRepository
     */
    private $userRepository;

    public function __construct(EventSourcedAggregateRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(RegisterUser $command)
    {
        $user = User::register(
            Uuid::fromString($command->id),
            $command->username,
            $command->nickname
        );

        $this->userRepository->save($user);
    }
}
