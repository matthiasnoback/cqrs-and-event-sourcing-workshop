<?php
declare(strict_types = 1);

namespace Meetup;

use EventSourcing\Aggregate\Event;
use EventSourcing\Aggregate\EventCapabilities;

final class MeetupRescheduled implements Event
{
    use EventCapabilities;

    /**
     * @var \DateTimeInterface
     */
    private $newDate;

    public function __construct(\DateTimeInterface $newDate)
    {
        $this->newDate = $newDate;
    }
}
