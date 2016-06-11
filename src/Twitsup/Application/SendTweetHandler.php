<?php

namespace Twitsup\Application;

use EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use Ramsey\Uuid\Uuid;
use Twitsup\Domain\Model\Tweet\Tweet;

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

    public function __invoke(SendTweet $command)
    {
        $tweet = Tweet::send(Uuid::uuid4(), Uuid::fromString($command->userId), $command->text);

        $this->repository->save($tweet);
    }
}
