<?php

namespace Twitsup\ReadModel;

use Ramsey\Uuid\UuidInterface;
use Twitsup\Domain\Model\Subscription\UserFollowed;
use Twitsup\Domain\Model\Subscription\UserStartedFollowing;
use Twitsup\Domain\Model\Subscription\UserUnfollowed;

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

    public function onUserStartedFollowing(UserStartedFollowing $event)
    {
        $this->follow($event->followerId(), $event->followeeId());
    }

    public function onUserFollowed(UserFollowed $event)
    {
        $this->follow($event->followerId(), $event->followeeId());
    }

    public function onUserUnfollowed(UserUnfollowed $event)
    {
        $this->unfollow($event->followerId(), $event->followeeId());
    }

    private function follow(UuidInterface $followerId, UuidInterface $followeeId)
    {
        $this->repository->follow((string)$followerId, (string)$followeeId);
    }

    private function unfollow(UuidInterface $followerId, UuidInterface $followeeId)
    {
        $this->repository->unfollow((string)$followerId, (string)$followeeId);
    }
}
