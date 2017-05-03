<?php
declare(strict_types=1);

namespace Twitsup\Domain\Model\Tweet;

use Common\EventSourcing\Aggregate\EventSourcedAggregateCapabilities;
use Common\EventSourcing\Aggregate\EventSourcedAggregate;
use Twitsup\Domain\Model\User\UserId;

final class Tweet implements EventSourcedAggregate
{
    use EventSourcedAggregateCapabilities;

    private $id;

    public static function send(TweetId $tweetId, UserId $userId, string $text, TweetedAt $tweetedAt): Tweet
    {
        $instance = new static();

        $instance->recordThat(new Tweeted($tweetId, $userId, $text, $tweetedAt));

        return $instance;
    }

    private function whenTweeted(Tweeted $event): void
    {
        $this->id = $event->tweetId();
    }
}
