<?php

namespace {

    use Doctrine\DBAL\DriverManager;
    use My\Model\User;
    use Rhumsaa\Uuid\Uuid;
    use Simple\EventStore\DoctrineDbalStorageFacility;
    use Simple\EventStore\EventStore;
    use Twitter\Domain\Model\Message;

    require __DIR__ . '/vendor/autoload.php';

    $connectionParams = array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/var/temp.sqlite'
    );

    $connection = DriverManager::getConnection($connectionParams);
    $tableName = 'simple_event_store';
    $storageFacility = new DoctrineDbalStorageFacility($connection, $tableName);

    //$storageFacility->setUp();

    $eventStore = new EventStore($storageFacility);

    $message = Message::createWithText(Uuid::uuid4(), 'The text of the message');

    $eventStore->append(Message::class, (string) $message->id(), $message->popRecordedEvents());

    $message = $eventStore->reconstitute(Message::class, (string) $message->id());
    print_r($message);
}
