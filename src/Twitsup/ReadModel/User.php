<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

final class User
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    public function id(): string
    {
        return $this->id;
    }
}
