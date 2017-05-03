<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

use Twitsup\Domain\Model\Subscription\UserFollowed;
use Twitsup\Domain\Model\Subscription\UserStartedFollowing;
use Twitsup\Domain\Model\User\UserId;

final class FollowersProjector
{
    /**
     * @var FollowersRepository
     */
    private $repository;

    public function __construct(FollowersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function onUserStartedFollowing(UserStartedFollowing $event): void
    {
        $this->follow($event->followerId(), $event->followeeId());
    }

    public function onUserFollowed(UserFollowed $event): void
    {
        $this->follow($event->followerId(), $event->followeeId());
    }

    private function follow(UserId $followerId, UserId $followeeId): void
    {
        $this->repository->follow((string)$followerId, (string)$followeeId);
    }
}
