<?php
declare(strict_types=1);

namespace MeetupManagement\Domain\Model;

final class ScheduledDate
{
    /**
     * @var string
     */
    private $date;

    private function __construct(string $date)
    {
        $this->date = $date;
    }

    public static function fromString(string $date): ScheduledDate
    {
        return new self($date);
    }
}
