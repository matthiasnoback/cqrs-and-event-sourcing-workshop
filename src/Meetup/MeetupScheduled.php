<?php
declare(strict_types = 1);

namespace Meetup;

use EventSourcing\Aggregate\Event;
use EventSourcing\Aggregate\EventCapabilities;

final class MeetupScheduled implements Event
{
    use EventCapabilities;

    /**
     * @var string
     */
    private $id;
    /**
     * @var \DateTimeInterface
     */
    private $date;
    /**
     * @var string
     */
    private $title;

    public function __construct(string $id, \DateTimeInterface $date, string $title)
    {
        $this->id = $id;
        $this->date = $date;
        $this->title = $title;
    }
}
