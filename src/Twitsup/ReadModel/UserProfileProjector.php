<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

use Twitsup\Domain\Model\User\UserRegistered;

final class UserProfileProjector
{
    /**
     * @var UserProfileRepository
     */
    private $repository;

    public function __construct(UserProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UserRegistered $event): void
    {
        $userProfile = new UserProfile();
        $userProfile->id = (string)$event->id();
        $userProfile->username = (string)$event->username();
        $userProfile->nickname = (string)$event->nickname();

        $this->repository->save($userProfile);
    }
}
