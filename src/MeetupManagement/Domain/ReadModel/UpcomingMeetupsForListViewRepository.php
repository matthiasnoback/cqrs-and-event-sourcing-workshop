<?php
declare(strict_types=1);

namespace MeetupManagement\Domain\ReadModel;

interface UpcomingMeetupsForListViewRepository
{
    public function byId(string $id) : UpcomingMeetupForListView;

    public function remove(string $id): void;

    public function save(string $id, UpcomingMeetupForListView $meetup);
}
