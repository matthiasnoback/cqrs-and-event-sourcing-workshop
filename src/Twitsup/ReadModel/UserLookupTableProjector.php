<?php

namespace Twitsup\ReadModel;

use Twitsup\Domain\Model\User\UserRegistered;

class UserLookupTableProjector
{
    /**
     * @var UserLookupTableRepository
     */
    private $repository;

    public function __construct(UserLookupTableRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UserRegistered $event)
    {
        $this->repository->save([
            'id' => (string)$event->id(),
            'username' => (string)$event->username()
        ]);
    }
}
