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

    public static function schedule(string $id, \DateTimeInterface $date, string $title) : Meetup
    {
        $meetup = new self();
        $meetup->id = $id;
        $meetup->date = $date;
        $meetup->title = $title;

        return $meetup;
    }

    public function reschedule(\DateTimeInterface $newDate)
    {
        $this->date = $newDate;
    }

    public function cancel()
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
