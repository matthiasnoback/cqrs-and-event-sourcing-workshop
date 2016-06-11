<?php

namespace Twitsup\Domain\Model\Tweet;

use EventSourcing\Aggregate\EventSourcingCapabilities;
use EventSourcing\Aggregate\EventSourcedAggregate;
use Ramsey\Uuid\UuidInterface;

final class Tweet implements EventSourcedAggregate
{
    use EventSourcingCapabilities;

    private $id;

    public static function send(UuidInterface $tweetId, UuidInterface $userId, string $text) : Tweet
    {
        $instance = new static();
        $instance->recordThat(new Tweeted($tweetId, $userId, $text));

        return $instance;
    }
    
    private function whenTweeted(Tweeted $event)
    {
        $this->id = $event->tweetId();
    }
}
