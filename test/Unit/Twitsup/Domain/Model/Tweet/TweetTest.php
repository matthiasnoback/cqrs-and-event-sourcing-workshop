<?php
declare(strict_types=1);

namespace Test\Unit\Twitsup\Domain\Model\Tweet;

use Twitsup\Domain\Model\Tweet\Tweet;
use Twitsup\Domain\Model\Tweet\Tweeted;
use Twitsup\Domain\Model\Tweet\TweetedAt;
use Twitsup\Domain\Model\Tweet\TweetId;
use Twitsup\Domain\Model\User\UserId;

class TweetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function a_tweet_can_be_tweeted()
    {
        $tweetId = TweetId::fromString('6682ffa9-2733-4670-b89d-1e47550f6986');
        $userId = UserId::fromString('831a5fde-f6b1-49a9-b999-668abf588fa5');
        $text = 'The text';
        $tweetedAt = TweetedAt::fromDateTime(new \DateTimeImmutable('now'));

        $tweet = Tweet::send($tweetId, $userId, $text, $tweetedAt);

        $this->assertEquals([new Tweeted($tweetId, $userId, $text, $tweetedAt)], $tweet->popRecordedEvents());
    }
}
