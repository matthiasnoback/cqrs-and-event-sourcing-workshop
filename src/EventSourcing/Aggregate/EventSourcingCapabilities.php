<?php

namespace EventSourcing\Aggregate;

/**
 * Using this trait in an event-sourced aggregate will make it fully compliant to the contract defined by the
 * `EventSourced` interface.
 */
trait EventSourcingCapabilities
{
    private $id;

    /**
     * @var object[]
     */
    private $recordedEvents = [];

    /**
     * Enforce construction through either a named constructor or the `reconstitute()` method.
     */
    private function __construct()
    {
    }

    public function popRecordedEvents()
    {
        $recordedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $recordedEvents;
    }

    public function id() : string
    {
        return $this->id;
    }

    public static function reconstitute(\Iterator $events)
    {
        $instance = new static();

        foreach ($events as $event) {
            $instance->apply($event);
        }

        return $instance;
    }

    private function recordThat(Event $event)
    {
        $enrichedEvent = $event
            ->withMetadata('created_at', new \DateTimeImmutable());

        $this->recordedEvents[] = $enrichedEvent;
        $this->apply($enrichedEvent);
    }
    
    private function apply(Event $event)
    {
        $parts = explode('\\', (get_class($event)));
        $eventName = end($parts);
        $name = 'when' . $eventName;
        call_user_func([$this, $name], $event);
    }
}
