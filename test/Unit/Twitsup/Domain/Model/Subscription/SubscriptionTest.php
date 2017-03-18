<?php

namespace Test\Unit\Twitsup\Domain\Model\Subscription;

use EventSourcing\Aggregate\Testing\RecordedEventsEqual;
use Ramsey\Uuid\Uuid;
use Twitsup\Domain\Model\Subscription\AUserCanNotFollowThemselves;
use Twitsup\Domain\Model\Subscription\Subscription;
use Twitsup\Domain\Model\Subscription\UserFollowed;
use Twitsup\Domain\Model\Subscription\UserStartedFollowing;
use Twitsup\Domain\Model\Subscription\UserUnfollowed;

final class SubscriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function a_user_can_not_follow_themselves()
    {
        $subscriptionId = Uuid::uuid4();
        $followerId = Uuid::uuid4();

        $this->expectException(AUserCanNotFollowThemselves::class);
        Subscription::startFollowing($subscriptionId, $followerId, $followerId);
    }

    /**
     * @test
     */
    public function a_user_can_start_following_another_user()
    {
        $subscriptionId = Uuid::uuid4();
        $followerId = Uuid::uuid4();
        $followeeId = Uuid::uuid4();
        $subscription = Subscription::startFollowing($subscriptionId, $followerId, $followeeId);

        self::assertThat([
            new UserStartedFollowing($subscriptionId, $followerId, $followeeId)
        ], new RecordedEventsEqual($subscription));
    }

    /**
     * @test
     */
    public function a_user_can_be_unfollowed()
    {
        $subscriptionId = Uuid::uuid4();
        $followerId = Uuid::uuid4();
        $followeeId = Uuid::uuid4();
        $subscription = Subscription::startFollowing($subscriptionId, $followerId, $followeeId);

        $subscription->unfollow();

        self::assertThat([
            new UserStartedFollowing($subscriptionId, $followerId, $followeeId),
            new UserUnfollowed($subscriptionId, $followerId, $followeeId)
        ], new RecordedEventsEqual($subscription));
    }

    /**
     * @test
     */
    public function a_user_can_be_followed_again()
    {
        $subscriptionId = Uuid::uuid4();
        $followerId = Uuid::uuid4();
        $followeeId = Uuid::uuid4();
        $subscription = Subscription::startFollowing($subscriptionId, $followerId, $followeeId);

        $subscription->unfollow();
        $subscription->follow();

        self::assertThat([
            new UserStartedFollowing($subscriptionId, $followerId, $followeeId),
            new UserUnfollowed($subscriptionId, $followerId, $followeeId),
            new UserFollowed($subscriptionId, $followerId, $followeeId),
        ], new RecordedEventsEqual($subscription));
    }
}
