<?php
declare(strict_types=1);

namespace Twitsup\Application;

use Common\EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use Twitsup\Domain\Model\User\User;
use Twitsup\Domain\Model\User\UserId;
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

    public function __invoke(RegisterUser $command): void
    {
        if ($this->userLookupRepository->userWithUsernameExists($command->username)) {
            throw new \InvalidArgumentException(sprintf(
                'A user with username "%s" already exists',
                $command->username
            ));
        }

        $user = User::register(
            UserId::fromString($command->id),
            $command->username,
            $command->nickname
        );

        $this->userRepository->save($user);
    }
}
