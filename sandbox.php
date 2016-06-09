<?php

namespace {
    use EventSourcing\EventStore\EventStore;
    use Ramsey\Uuid\Uuid;
    use Twitsup\Domain\Model\Message;

    require __DIR__ . '/vendor/autoload.php';
    
    $container = require __DIR__ . '/app/container.php';
    
    $eventStore = $container->get(EventStore::class);
    /** @var $eventStore EventStore */

    $message = Message::createWithText(Uuid::uuid4(), 'The text of the message');

    $eventStore->append(Message::class, (string) $message->id(), $message->popRecordedEvents());

    $message = $eventStore->reconstitute(Message::class, (string) $message->id());
    print_r($message);
}
