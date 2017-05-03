<?php
declare(strict_types=1);

namespace Meetup;

final class MeetupRescheduled
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var \DateTimeInterface
     */
    private $newDate;

    public function __construct(string $id, \DateTimeInterface $newDate)
    {
        $this->id = $id;
        $this->newDate = $newDate;
    }

    public function id()
    {
        return $this->id;
    }

    public function newDate()
    {
        return $this->newDate;
    }
}
