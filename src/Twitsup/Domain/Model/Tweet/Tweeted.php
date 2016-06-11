<?php

namespace Twitsup\Domain\Model\Tweet;

use EventSourcing\Aggregate\Event;
use EventSourcing\Aggregate\EventCapabilities;
use Ramsey\Uuid\UuidInterface;

final class Tweeted implements Event
{
    use EventCapabilities;

    /**
     * @var UuidInterface
     */
    private $tweetId;

    /**
     * @var UuidInterface
     */
    private $userId;

    /**
     * @var string
     */
    private $text;

    public function __construct(UuidInterface $tweetId, UuidInterface $userId, string $text)
    {
        $this->tweetId = $tweetId;
        $this->userId = $userId;
        $this->text = $text;
    }

    /**
     * @return UuidInterface
     */
    public function tweetId() : UuidInterface
    {
        return $this->tweetId;
    }

    /**
     * @return UuidInterface
     */
    public function userId()
    {
        return $this->userId;
    }

    public function text() : string
    {
        return $this->text;
    }
}
