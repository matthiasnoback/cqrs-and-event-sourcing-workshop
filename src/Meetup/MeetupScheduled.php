<?php
declare(strict_types=1);

namespace Meetup;

final class MeetupScheduled
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

    public function __construct(string $id, \DateTimeInterface $date, string $title)
    {
        $this->id = $id;
        $this->date = $date;
        $this->title = $title;
    }

    public function id()
    {
        return $this->id;
    }

    public function date()
    {
        return $this->date;
    }

    public function title()
    {
        return $this->title;
    }
}
