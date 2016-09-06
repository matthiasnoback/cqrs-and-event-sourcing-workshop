<?php

use Meetup\Meetup;

require __DIR__ . '/vendor/autoload.php';

function it($m,$p){echo "\033[".($p?"32m✔":"31m✘")." It $m\033[0m\n"; if(!$p)$GLOBALS['f']=1;}function done(){if(@$GLOBALS['f'])die(1);}
function throws($exp,\Closure $cb){try{$cb();}catch(\Exception $e){return $e instanceof $exp;}return false;}

$id = '1234';
$date = new \DateTimeImmutable('2016-06-13');
$title = 'An evening with CQRS';
$meetup = Meetup::schedule($id, $date, $title);

it('should have the provided title', $meetup->title() == $title);
it('should be scheduled on the provided date', $meetup->date() == $date);
it('should not be cancelled', !$meetup->hasBeenCancelled());

$newDate = new \DateTimeImmutable('2016-07-13');
$meetup->reschedule($newDate);

it('should be rescheduled on the provided date', $meetup->date() == $newDate);
it('should not be cancelled', !$meetup->hasBeenCancelled());

$meetup->cancel();
it('should be cancelled', $meetup->hasBeenCancelled());
