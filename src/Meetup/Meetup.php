<?php

namespace Meetup;

final class Meetup
{
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

    private $events = [];

    public function popRecordedEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }

    public static function schedule(string $id, \DateTimeInterface $date, string $title): Meetup
    {
        $meetup = new self();

        $meetup->recordThat(new MeetupScheduled($id, $date, $title));

        return $meetup;
    }

    public static function reconstitute(array $events): Meetup
    {
        $meetup = new self();

        foreach ($events as $event) {
            $meetup->apply($event);
        }

        return $meetup;
    }

    private function recordThat($event): void
    {
        $this->events[] = $event;

        $this->apply($event);
    }

    private function apply($event): void
    {
        $parts = explode('\\', get_class($event));
        $lastPart = end($parts);
        $method = 'apply' . $lastPart;

        $this->$method($event);
    }

    private function applyMeetupScheduled(MeetupScheduled $event): void
    {
        $this->id = $event->id();
        $this->date = $event->date();
        $this->title = $event->title();
    }

    public function reschedule(\DateTimeInterface $newDate): void
    {
        if ($this->cancelled) {
            throw new CanNotRescheduleACancelledMeetup();
        }

        $this->recordThat(new MeetupRescheduled($this->id, $newDate));
    }

    private function applyMeetupRescheduled(MeetupRescheduled $event): void
    {
        $this->date = $event->newDate();
    }

    public function cancel(): void
    {
        $this->recordThat(new MeetupCancelled($this->id));
    }

    private function applyMeetupCancelled(MeetupCancelled $event): void
    {
        $this->cancelled = true;
    }

    public function hasBeenCancelled(): bool
    {
        return $this->cancelled;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function date(): \DateTimeInterface
    {
        return $this->date;
    }

    public function title(): string
    {
        return $this->title;
    }
}
