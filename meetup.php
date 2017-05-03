<?php

use Meetup\Meetup;
use Meetup\MeetupCancelled;
use Meetup\MeetupRescheduled;
use Meetup\MeetupScheduled;

require __DIR__ . '/vendor/autoload.php';

/*
 * The test framework
 */
function it($m,$p){echo "\033[".($p?"32m✔":"31m✘")." It $m\033[0m\n"; if(!$p)$GLOBALS['f']=1;}function done(){if(@$GLOBALS['f'])die(1);}
function throws($exp,\Closure $cb){try{$cb();}catch(\Exception $e){return $e instanceof $exp;}return false;}

/*
 * Scheduling
 */
$id = '1234';
$date = new \DateTimeImmutable('2016-06-13');
$title = 'An evening with CQRS';
$meetup = Meetup::schedule($id, $date, $title);

$allEvents = [];
$latestEvents = $meetup->popRecordedEvents();
$allEvents = array_merge($allEvents, $latestEvents);
it('should have recorded a MeetupScheduled event', [new MeetupScheduled($id, $date, $title)] == $latestEvents);
it('should have the provided id', $meetup->id() == $id);
it('should have the provided title', $meetup->title() == $title);
it('should be scheduled on the provided date', $meetup->date() == $date);
it('should not be cancelled', !$meetup->hasBeenCancelled());

/*
 * Rescheduling
 */
$newDate = new \DateTimeImmutable('2016-07-13');
$meetup->reschedule($newDate);

$latestEvents = $meetup->popRecordedEvents();
$allEvents = array_merge($allEvents, $latestEvents);
it('should have recorded a MeetupRescheduled event', [new MeetupRescheduled($id, $newDate)] == $latestEvents);
it('should be rescheduled on the provided date', $meetup->date() == $newDate);
it('should not be cancelled', !$meetup->hasBeenCancelled());

/*
 * Cancellation
 */
$meetup->cancel();

$latestEvents = $meetup->popRecordedEvents();
$allEvents = array_merge($allEvents, $latestEvents);
it('should have recorded a MeetupCancelled event', [new MeetupCancelled($id)] == $latestEvents);
it('should be cancelled', $meetup->hasBeenCancelled());

it('can not be rescheduled once it has been cancelled', throws(\Meetup\CanNotRescheduleACancelledMeetup::class, function () use ($meetup) {
    $meetup->reschedule(new \DateTimeImmutable('2016-08-12'));
}));

/*
 * Reconstitution
 */
$reconstitutedMeetup = Meetup::reconstitute($allEvents);

it('should have the provided id', $meetup->id() == $id);
it('should have the provided title', $reconstitutedMeetup->title() == $title);
it('should be rescheduled on the provided date', $reconstitutedMeetup->date() == $newDate);
it('should be cancelled', $reconstitutedMeetup->hasBeenCancelled());
