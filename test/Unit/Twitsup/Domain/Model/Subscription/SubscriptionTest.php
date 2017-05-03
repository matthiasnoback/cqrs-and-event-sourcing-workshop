<?php
declare(strict_types=1);

namespace Test\Unit\Twitsup\Domain\Model\Subscription;

use Twitsup\Domain\Model\Subscription\AUserCanNotFollowThemselves;
use Twitsup\Domain\Model\Subscription\Subscription;
use Twitsup\Domain\Model\Subscription\SubscriptionId;
use Twitsup\Domain\Model\Subscription\UserFollowed;
use Twitsup\Domain\Model\Subscription\UserStartedFollowing;
use Twitsup\Domain\Model\User\UserId;

final class SubscriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function a_user_can_not_follow_themselves()
    {
        $subscriptionId = $this->aSubscriptionId();
        $followerId = $this->aUserId();

        $this->expectException(AUserCanNotFollowThemselves::class);
        Subscription::startFollowing($subscriptionId, $followerId, $followerId);
    }

    /**
     * @test
     */
    public function a_user_can_start_following_another_user()
    {
        $subscriptionId = $this->aSubscriptionId();
        $followerId = $this->aUserId();
        $followeeId = $this->anotherUserId();
        $subscription = Subscription::startFollowing($subscriptionId, $followerId, $followeeId);

        $this->assertEquals([
            new UserStartedFollowing($subscriptionId, $followerId, $followeeId)
        ], $subscription->popRecordedEvents());
    }

    /**
     * @test
     */
    public function a_user_can_be_unfollowed()
    {
        $this->markTestIncomplete('Unfollowing needs to be implemented');

        $subscriptionId = $this->aSubscriptionId();
        $followerId = $this->aUserId();
        $followeeId = $this->anotherUserId();
        $subscription = Subscription::startFollowing($subscriptionId, $followerId, $followeeId);

        $subscription->unfollow();

        $this->assertEquals([
            new UserStartedFollowing($subscriptionId, $followerId, $followeeId),
            new UserUnfollowed($subscriptionId, $followerId, $followeeId)
        ], $subscription->popRecordedEvents());
    }

    /**
     * @test
     */
    public function a_user_can_be_followed_again()
    {
        $this->markTestIncomplete('Unfollowing needs to be implemented');

        $subscriptionId = $this->aSubscriptionId();
        $followerId = $this->aUserId();
        $followeeId = $this->anotherUserId();
        $subscription = Subscription::startFollowing($subscriptionId, $followerId, $followeeId);

        $subscription->unfollow();
        $subscription->follow();

        $this->assertEquals([
            new UserStartedFollowing($subscriptionId, $followerId, $followeeId),
            new UserUnfollowed($subscriptionId, $followerId, $followeeId),
            new UserFollowed($subscriptionId, $followerId, $followeeId),
        ], $subscription->popRecordedEvents());
    }

    private function aSubscriptionId(): SubscriptionId
    {
        return SubscriptionId::fromString('2aa704e5-d1ac-4a75-9ffe-0f73c3be7185');
    }

    private function aUserId(): UserId
    {
        return UserId::fromString('50f4fff9-9e17-4109-965d-8b7f10903201');
    }

    private function anotherUserId(): UserId
    {
        return UserId::fromString('0bcf8e3a-52fc-4512-853f-37a6bcfb05fc');
    }
}
