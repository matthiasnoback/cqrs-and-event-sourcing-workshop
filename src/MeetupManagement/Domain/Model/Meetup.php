<?php
declare(strict_types=1);

namespace MeetupManagement\Domain\Model;

final class Meetup
{
    /**
     * @var MeetupId
     */
    private $meetupId;

    /**
     * @var ScheduledDate
     */
    private $scheduledDate;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var bool
     */
    private $cancelled = false;

    private function __construct(MeetupId $meetupId, ScheduledDate $scheduledDate, Title $title)
    {
        $this->meetupId = $meetupId;
        $this->scheduledDate = $scheduledDate;
        $this->title = $title;
    }

    public static function schedule(MeetupId $meetupId, ScheduledDate $scheduledDate, Title $title): Meetup
    {
        return new self($meetupId, $scheduledDate, $title);
    }

    public function reschedule(ScheduledDate $newDate): void
    {
        if ($this->cancelled) {
            throw new \LogicException('You can not rescheduled a cancelled meetup');
        }

        $this->scheduledDate = $newDate;
    }

    public function cancel(): void
    {
        $this->cancelled = true;
    }

    public function hasBeenCancelled(): bool
    {
        return $this->cancelled;
    }

    public function meetupId(): MeetupId
    {
        return $this->meetupId;
    }

    public function scheduledDate(): ScheduledDate
    {
        return $this->scheduledDate;
    }

    public function title(): Title
    {
        return $this->title;
    }
}
