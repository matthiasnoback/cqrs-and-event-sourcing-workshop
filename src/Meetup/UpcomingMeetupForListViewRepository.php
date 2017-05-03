<?php

namespace Meetup;

final class UpcomingMeetupForListViewRepository
{
    private $meetups = [];

    /**
     * @param string $id
     * @return UpcomingMeetupForListView
     * @throws \RuntimeException
     */
    public function byId(string $id) : UpcomingMeetupForListView
    {
        if (!isset($this->meetups[$id])) {
            throw new \RuntimeException();
        }

        return $this->meetups[$id];
    }

    /**
     * @param string $id
     * @return void
     */
    public function remove(string $id)
    {
        unset($this->meetups[$id]);
    }

    /**
     * @param string $id
     * @param UpcomingMeetupForListView $meetup
     * @return void
     */
    public function save(string $id, UpcomingMeetupForListView $meetup)
    {
        $this->meetups[$id] = $meetup;
    }

    public function all(): array
    {
        return $this->meetups;
    }
}
