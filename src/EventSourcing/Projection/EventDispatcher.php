<?php

namespace EventSourcing\Projection;

use EventSourcing\Aggregate\Event;

final class EventDispatcher
{
    private $listeners = [];

    public function on(string $eventType, callable $listener)
    {
        $this->listeners[$eventType][] = $listener;
    }

    public function dispatch(Event $event)
    {
        $listeners = $this->listeners[get_class($event)] ?? [];
        foreach ($listeners as $listener) {
            call_user_func($listener, $event);
        }
    }
}
