<?php

namespace Twitsup\Application;

use EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use Ramsey\Uuid\Uuid;
use Twitsup\Domain\Model\User\User;
use Twitsup\ReadModel\UserLookupRepository;

final class RegisterUserHandler
{
    /**
     * @var EventSourcedAggregateRepository
     */
    private $userRepository;
    /**
     * @var UserLookupRepository
     */
    private $userLookupRepository;

    public function __construct(EventSourcedAggregateRepository $userRepository, UserLookupRepository $userLookupRepository)
    {
        $this->userRepository = $userRepository;
        $this->userLookupRepository = $userLookupRepository;
    }

    public function __invoke(RegisterUser $command)
    {
        if ($this->userLookupRepository->userWithUsernameExists($command->username)) {
            throw new \InvalidArgumentException(sprintf(
                'A user with username "%s" already exists',
                $command->username
            ));
        }

        $user = User::register(
            Uuid::fromString($command->id),
            $command->username,
            $command->nickname
        );

        $this->userRepository->save($user);
    }
}
