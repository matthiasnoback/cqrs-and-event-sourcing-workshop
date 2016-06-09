<?php

namespace EventSourcing\Aggregate\Testing;

use Assert\Assertion;
use EventSourcing\Aggregate\Event;
use EventSourcing\Aggregate\EventSourcedAggregate;

class RecordedEventsEqual extends \PHPUnit_Framework_Constraint
{
    /**
     * @var EventSourcedAggregate
     */
    private $aggregate;

    public function __construct(EventSourcedAggregate $aggregate)
    {
        parent::__construct();

        $this->aggregate = $aggregate;
    }

    protected function matches($expectedEvents)
    {
        Assertion::allIsInstanceOf($expectedEvents, Event::class);

        $actualEvents = $this->aggregate->popRecordedEvents();

        if (count($expectedEvents) !== count($actualEvents)) {
            return false;
        }

        foreach ($expectedEvents as $key => $expectedEvent) {
            $actualEvent = $actualEvents[$key];
            if (get_class($expectedEvent) !== get_class($actualEvent)) {
                return false;
            }

            $this->clearMetadata($actualEvent);
            $this->clearMetadata($expectedEvent);

            $constraint = new \PHPUnit_Framework_Constraint_IsEqual($expectedEvent);
            if (!$constraint->evaluate($actualEvent, '', true)) {
                return false;
            }
        }

        return true;
    }

    public function toString()
    {
        return '';
    }

    private function clearMetadata(Event $event)
    {
        /*
         * This is a hack; we don't know if the event actually has a property called "metadata" (the trait does!)
         */
        $metadataReflectionProperty = new \ReflectionProperty($event, 'metadata');
        $metadataReflectionProperty->setAccessible(true);
        $metadataReflectionProperty->setValue($event, []);
    }
}
