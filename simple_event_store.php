<?php

namespace {

    use Doctrine\DBAL\DriverManager;
    use Doctrine\DBAL\Exception\TableExistsException;
    use EventSourcing\EventStore\Storage\DoctrineDbalStorageFacility;
    use EventSourcing\EventStore\EventStore;
    use Ramsey\Uuid\Uuid;
    use Twitter\Domain\Model\Message;

    require __DIR__ . '/vendor/autoload.php';

    $connectionParams = array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/var/temp.sqlite'
    );

    $connection = DriverManager::getConnection($connectionParams);
    $tableName = 'simple_event_store';
    $storageFacility = new DoctrineDbalStorageFacility($connection, $tableName);

    try {
        $storageFacility->setUp();
    } catch (TableExistsException $exception) {
        // mute
    }

    $eventStore = new EventStore($storageFacility);

    $message = Message::createWithText(Uuid::uuid4(), 'The text of the message');

    $eventStore->append(Message::class, (string) $message->id(), $message->popRecordedEvents());

    $message = $eventStore->reconstitute(Message::class, (string) $message->id());
    print_r($message);
}
