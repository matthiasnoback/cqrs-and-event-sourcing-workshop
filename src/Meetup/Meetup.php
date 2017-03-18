<?php

namespace Meetup;

use EventSourcing\Aggregate\EventSourcedAggregate;
use EventSourcing\Aggregate\EventSourcingCapabilities;

final class Meetup implements EventSourcedAggregate
{
    use EventSourcingCapabilities;

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

    /**
     * @var bool
     */
    private $cancelled = false;

    public static function schedule(string $id, \DateTimeInterface $date, string $title) : Meetup
    {
        $meetup = new static();

        $meetup->recordThat(new MeetupScheduled($id, $date, $title));

        return $meetup;
    }

    public function whenMeetupScheduled(MeetupScheduled $event)
    {

    }

    public function reschedule(\DateTimeInterface $newDate)
    {
        if ($this->cancelled) {
            throw new \LogicException('Can not reschedule a cancelled meetup');
        }

        $this->recordThat(new MeetupRescheduled($newDate));
    }

    public function whenMeetupRescheduled(MeetupRescheduled $event)
    {

    }

    public function cancel()
    {
        $this->recordThat(new MeetupCancelled());
    }

    public function whenMeetupCancelled(MeetupCancelled $event)
    {
        $this->cancelled = true;
    }

    public function hasBeenCancelled() : bool
    {
        return $this->cancelled;
    }

    public function id() : string
    {
        return $this->id;
    }

    public function date() : \DateTimeInterface
    {
        return $this->date;
    }

    public function title() : string
    {
        return $this->title;
    }
}
