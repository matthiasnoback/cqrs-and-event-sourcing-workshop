<?php

namespace Twitsup\ReadModel;

use Twitsup\Domain\Model\MessageCreated;

class MessagesProjector
{
    /**
     * @var AllMessagesListRepository
     */
    private $messagesRepository;

    public function __construct(AllMessagesListRepository $messagesRepository)
    {
        $this->messagesRepository = $messagesRepository;
    }

    public function __invoke(MessageCreated $event)
    {
        $this->messagesRepository->save([
            'id' => (string)$event->id(),
            'text' => $event->text()
        ]);
    }
}
