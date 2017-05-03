<?php
declare(strict_types=1);

namespace Test\Unit\MeetupManagement\Domain\Model;

use MeetupManagement\Domain\Model\Meetup;
use MeetupManagement\Domain\Model\MeetupId;
use MeetupManagement\Domain\Model\ScheduledDate;
use MeetupManagement\Domain\Model\Title;

class MeetupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_has_an_id_title_and_scheduled_date(): void
    {
        $meetupId = MeetupId::fromString('00de6d41-f99d-488e-9753-03932e79d7d0');
        $scheduledDate = ScheduledDate::fromString('2016-06-13');
        $title = Title::fromString('An evening with CQRS');

        $meetup = Meetup::schedule($meetupId, $scheduledDate, $title);

        $this->assertEquals($meetupId, $meetup->meetupId());
        $this->assertEquals($title, $meetup->title());
        $this->assertEquals($scheduledDate, $meetup->scheduledDate());
    }

    /**
     * @test
     */
    public function it_can_be_rescheduled(): void
    {
        $meetup = Meetup::schedule(
            MeetupId::fromString('00de6d41-f99d-488e-9753-03932e79d7d0'),
            ScheduledDate::fromString('2016-06-13'),
            Title::fromString('An evening with CQRS')
        );

        $newDate = ScheduledDate::fromString('2016-07-14');
        $meetup->reschedule($newDate);

        $this->assertEquals($newDate, $meetup->scheduledDate());
    }

    /**
     * @test
     */
    public function it_can_be_cancelled(): void
    {
        $meetup = Meetup::schedule(
            MeetupId::fromString('00de6d41-f99d-488e-9753-03932e79d7d0'),
            ScheduledDate::fromString('2016-06-13'),
            Title::fromString('An evening with CQRS')
        );
        $this->assertFalse($meetup->hasBeenCancelled());

        $meetup->cancel();

        $this->assertTrue($meetup->hasBeenCancelled());
    }

    /**
     * @test
     */
    public function a_cancelled_meetup_can_not_be_rescheduled(): void
    {
        $meetup = Meetup::schedule(
            MeetupId::fromString('00de6d41-f99d-488e-9753-03932e79d7d0'),
            ScheduledDate::fromString('2016-06-13'),
            Title::fromString('An evening with CQRS')
        );
        $meetup->cancel();

        $this->expectException(\LogicException::class);

        $meetup->reschedule(ScheduledDate::fromString('2016-07-14'));
    }
}
