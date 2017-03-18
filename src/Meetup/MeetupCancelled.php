<?php
declare(strict_types = 1);

namespace Meetup;

use EventSourcing\Aggregate\Event;
use EventSourcing\Aggregate\EventCapabilities;

final class MeetupCancelled implements Event
{
    use EventCapabilities;
}
