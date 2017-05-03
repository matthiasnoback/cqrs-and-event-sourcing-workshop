<?php
declare(strict_types=1);

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
    $registerUser1 = new RegisterUser();
    $registerUser1->id = (string)Uuid::uuid4();
    $registerUser1->username = 'matthiasnoback';
    $registerUser1->nickname = 'Matthias Noback';
    $registerUserHandler($registerUser1);

    $registerUser2 = new RegisterUser();
    $registerUser2->id = (string)Uuid::uuid4();
    $registerUser2->username = 'mennobacker';
    $registerUser2->nickname = 'Menno Backer';
    $registerUserHandler($registerUser2);

    $followUser = new FollowUser();
    $followUser->followerUsername = 'matthiasnoback';
    $followUser->followeeUsername = 'mennobacker';
    $followUserHandler = $container[FollowUserHandler::class];
    $followUserHandler($followUser);

    $sendTweetHandler = $container[SendTweetHandler::class];
    $sendTweet = new SendTweet();
    $sendTweet->userId = $registerUser2->id;
    $sendTweet->text = 'The text of the message';

    $sendTweetHandler($sendTweet);
}
