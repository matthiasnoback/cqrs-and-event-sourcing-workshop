<?php

namespace Meetup;

final class Meetup
{
    private $date;
    private $title;
    private $cancelled = false;

    public static function schedule(\DateTimeInterface $date, $title)
    {
        $meetup = new self();
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

    public function date() : \DateTimeInterface
    {
        return $this->date;
    }

    public function title() : string
    {
        return $this->title;
    }
}
