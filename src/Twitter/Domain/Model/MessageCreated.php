<?php

namespace Twitter\Domain\Model;

use Rhumsaa\Uuid\Uuid;
use Simple\EventStore\Event;
use Simple\EventStore\EventCapabilities;

final class MessageCreated implements Event
{
    use EventCapabilities;

    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @return Uuid
     */
    public function id() : Uuid
    {
        return $this->id;
    }

    public function __construct(Uuid $id, string $text)
    {
        $this->text = $text;
        $this->id = $id;
    }

    public function text() : string
    {
        return $this->text;
    }
}
