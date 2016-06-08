<?php

namespace Twitter\Domain\Model;

use Rhumsaa\Uuid\Uuid;
use Simple\EventStore\EventSourced;
use Simple\EventStore\EventSourcingCapabilities;

final class Message implements EventSourced
{
    use EventSourcingCapabilities;

    private $text;
    private $id;

    public static function createWithText(Uuid $id, $text)
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
