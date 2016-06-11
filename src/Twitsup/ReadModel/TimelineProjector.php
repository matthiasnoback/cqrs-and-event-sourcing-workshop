<?php

namespace Twitsup\ReadModel;

use Twitsup\Domain\Model\Tweet\Tweeted;

final class TimelineProjector
{
    /**
     * @var FollowersRepository
     */
    private $followersRepository;

    /**
     * @var UserProfileRepository
     */
    private $userProfileRepository;

    /**
     * @var TimelineRepository
     */
    private $timelineRepository;

    public function __construct(
        FollowersRepository $followersRepository,
        UserProfileRepository $userProfileRepository,
        TimelineRepository $timelineRepository
    ) {
        $this->followersRepository = $followersRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->timelineRepository = $timelineRepository;
    }

    public function __invoke(Tweeted $event)
    {
        $tweetedAt = $event->metadata('created_at');
        $userProfile = $this->userProfileRepository->getByUserId((string)$event->userId());
        $followerIds = $this->followersRepository->getFollowerIds((string)$event->userId());

        foreach ($followerIds as $followerId) {
            $this->timelineRepository->addToTimeline($followerId, $userProfile, $event->text(), $tweetedAt);
        }
    }
}
