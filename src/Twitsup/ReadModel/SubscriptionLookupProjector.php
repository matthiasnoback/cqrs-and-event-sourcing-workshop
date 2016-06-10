<?php

namespace Twitsup\ReadModel;

use Twitsup\Domain\Model\User\UserRegistered;

class SubscriptionLookupProjector
{
    /**
     * @var UserLookupRepository
     */
    private $repository;

    public function __construct(UserLookupRepository $repository)
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
