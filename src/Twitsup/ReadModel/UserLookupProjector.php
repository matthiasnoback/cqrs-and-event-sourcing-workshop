<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

use Twitsup\Domain\Model\User\UserRegistered;

class UserLookupProjector
{
    /**
     * @var UserLookupRepository
     */
    private $repository;

    public function __construct(UserLookupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UserRegistered $event): void
    {
        $user = new User();
        $user->id = (string)$event->id();
        $user->username = (string)$event->username();

        $this->repository->save($user);
    }
}
