<?php

namespace Twitter\Domain\Model;

use EventSourcing\Aggregate\EventSourcingCapabilities;
use EventSourcing\Aggregate\EventSourcedAggregate;
use Ramsey\Uuid\UuidInterface;

final class Message implements EventSourcedAggregate
{
    use EventSourcingCapabilities;

    private $text;
    private $id;

    public static function createWithText(UuidInterface $id, $text)
    {
        $instance = new static();
        $instance->recordThat(new MessageCreated($id, $text));

        return $instance;
    }
    
    public function whenMessageCreated(MessageCreated $event)
    {
        $this->id = $event->id();
        $this->text = $event->text();
    }
}
