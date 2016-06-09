<?php

namespace {

    use EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
    use Ramsey\Uuid\Uuid;
    use Twitsup\Domain\Model\Tweet\Tweet;
    use Twitsup\Domain\Model\User\User;

    require __DIR__ . '/vendor/autoload.php';

    $container = require __DIR__ . '/app/container.php';
    
    $user = User::register(Uuid::uuid4(), 'matthiasnoback', 'Matthias Noback');
    /** @var EventSourcedAggregateRepository $userRepository */
    $userRepository = $container->get('Twitsup\Domain\Model\UserRepository');
    $userRepository->save($user);

    $tweet = Tweet::createWithText(Uuid::uuid4(), 'The text of the message');

    /** @var EventSourcedAggregateRepository $tweetRepository */
    $tweetRepository = $container->get('Twitsup\Domain\Model\MessageRepository');

    $tweetRepository->save($tweet);

    $reconstitutedTweet = $tweetRepository->getById((string) $tweet->id());

    print_r($reconstitutedTweet);
}
