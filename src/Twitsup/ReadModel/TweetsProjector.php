<?php

namespace Twitsup\ReadModel;

use Twitsup\Domain\Model\Tweet\Tweeted;

class TweetsProjector
{
    /**
     * @var AllTweetsListRepository
     */
    private $messagesRepository;

    public function __construct(AllTweetsListRepository $messagesRepository)
    {
        $this->messagesRepository = $messagesRepository;
    }

    public function __invoke(Tweeted $event)
    {
        $this->messagesRepository->save([
            'id' => (string)$event->id(),
            'text' => (string)$event->text()
        ]);
    }
}
