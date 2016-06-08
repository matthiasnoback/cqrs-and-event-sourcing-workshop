<?php

namespace Twitter\Domain\Model;

use EventSourcing\Aggregate\Event;
use EventSourcing\Aggregate\EventCapabilities;
use Ramsey\Uuid\Uuid;

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
