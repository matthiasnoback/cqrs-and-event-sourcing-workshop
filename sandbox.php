<?php

namespace {

    use Ramsey\Uuid\Uuid;
    use Twitsup\Application\FollowUser;
    use Twitsup\Application\FollowUserHandler;
    use Twitsup\Application\RegisterUser;
    use Twitsup\Application\RegisterUserHandler;
    use Twitsup\Application\SendTweet;
    use Twitsup\Application\SendTweetHandler;

    require __DIR__ . '/vendor/autoload.php';

    $container = require __DIR__ . '/app/container.php';

    $registerUserHandler = $container[RegisterUserHandler::class];
    $registerUser = new RegisterUser();
    $registerUser->id = (string)Uuid::uuid4();
    $registerUser->username = 'matthiasnoback';
    $registerUser->nickname = 'Matthias Noback';
    $registerUserHandler($registerUser);

    $registerUser = new RegisterUser();
    $registerUser->id = (string)Uuid::uuid4();
    $registerUser->username = 'mennobacker';
    $registerUser->nickname = 'Menno Backer';
    $registerUserHandler($registerUser);

    $followUser = new FollowUser();
    $followUser->followerUsername = 'matthiasnoback';
    $followUser->followeeUsername = 'mennobacker';
    $followUserHandler = $container[FollowUserHandler::class];
    $followUserHandler($followUser);

    $sendTweetHandler = $container[SendTweetHandler::class];
    $sendTweet = new SendTweet();
    $sendTweet->userId = $registerUser->id;
    $sendTweet->text = 'The text of the message';

    $sendTweetHandler($sendTweet);
}
