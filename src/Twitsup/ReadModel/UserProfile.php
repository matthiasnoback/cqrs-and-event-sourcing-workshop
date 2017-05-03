<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

final class UserProfile
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $nickname;

    public function id(): string
    {
        return $this->id;
    }
}
