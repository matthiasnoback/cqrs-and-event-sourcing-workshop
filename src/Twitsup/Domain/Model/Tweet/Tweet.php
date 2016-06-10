<?php

namespace Twitsup\Domain\Model\Tweet;

use EventSourcing\Aggregate\EventSourcingCapabilities;
use EventSourcing\Aggregate\EventSourcedAggregate;
use Ramsey\Uuid\UuidInterface;

final class Tweet implements EventSourcedAggregate
{
    use EventSourcingCapabilities;

    private $id;

    public static function send(UuidInterface $id, string $text) : Tweet
    {
        $instance = new static();
        $instance->recordThat(new Tweeted($id, $text));

        return $instance;
    }
    
    private function whenTweeted(Tweeted $event)
    {
        $this->id = $event->id();
    }
}
