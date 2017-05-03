<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

final class Subscription
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $followerId;

    /**
     * @var string
     */
    public $followeeId;

    public function id(): string
    {
        return $this->id;
    }
}
