<?php
declare(strict_types=1);

namespace Twitsup\Application;

use Common\EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use Ramsey\Uuid\Uuid;
use Twitsup\Domain\Model\Tweet\Tweet;
use Twitsup\Domain\Model\Tweet\TweetedAt;
use Twitsup\Domain\Model\Tweet\TweetId;
use Twitsup\Domain\Model\User\UserId;

final class SendTweetHandler
{
    /**
     * @var EventSourcedAggregateRepository
     */
    private $repository;

    public function __construct(EventSourcedAggregateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SendTweet $command): void
    {
        $tweet = Tweet::send(
            TweetId::fromString((string)Uuid::uuid4()),
            UserId::fromString($command->userId),
            $command->text,
            TweetedAt::fromDateTime(new \DateTimeImmutable('now'))
        );

        $this->repository->save($tweet);
    }
}
