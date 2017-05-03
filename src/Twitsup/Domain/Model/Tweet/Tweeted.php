<?php
declare(strict_types=1);

namespace Twitsup\Domain\Model\Tweet;

use Twitsup\Domain\Model\User\UserId;

final class Tweeted
{
    /**
     * @var TweetId
     */
    private $tweetId;

    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $text;

    /**
     * @var TweetedAt
     */
    private $tweetedAt;

    public function __construct(TweetId $tweetId, UserId $userId, string $text, TweetedAt $tweetedAt)
    {
        $this->tweetId = $tweetId;
        $this->userId = $userId;
        $this->text = $text;
        $this->tweetedAt = $tweetedAt;
    }

    public function tweetId() : TweetId
    {
        return $this->tweetId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function text() : string
    {
        return $this->text;
    }

    public function tweetedAt(): TweetedAt
    {
        return $this->tweetedAt;
    }
}
