<?php

namespace {

    require __DIR__ . '/vendor/autoload.php';

    use My\Model\User;
    use My\Model\UserRepository;
    use Prooph\EventStore\EventStore;

    $container = require __DIR__ . '/app/container.php';

    $eventStore = $container->get(EventStore::class);
    /** @var EventStore $eventStore  */
    $userRepository = $container->get(UserRepository::class);
    /** @var UserRepository $userRepository */

    //Ok lets start a new transaction and create a user
    $eventStore->beginTransaction();

    $user = User::nameNew('John Doe');

    $userRepository->add($user);

    //Before we commit the transaction let's attach a listener to check that the UserWasCreated event is published after commit

    $eventStore->commit();

    $userId = $user->userId();

    unset($user);

    //Ok, great. Now let's see how we can grab the user from the repository and change the name

    //First we need to start a new transaction
    $eventStore->beginTransaction();

    //The repository automatically tracks changes of the user...
    $loadedUser = $userRepository->get($userId);

    $loadedUser->changeName('Max Mustermann');

    //... so we only need to commit the transaction and the UserWasRenamed event should be recorded
    //(check output of the previously attached listener)
    $eventStore->commit();
}
