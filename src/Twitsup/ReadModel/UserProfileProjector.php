<?php

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
    
    public function __invoke(UserRegistered $event)
    {
        $this->repository->save([
            'id' => (string)$event->id(),
            'username' => (string)$event->username(),
            'nickname' => (string)$event->nickname(),
        ]);
    }
}
