<?php
declare(strict_types=1);

namespace Twitsup\Domain\Model\User;

final class UserRegistered
{
    /**
     * @var UserId
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $nickname;

    public function __construct(UserId $id, string $username, string $nickname)
    {
        $this->id = $id;
        $this->username = $username;
        $this->nickname = $nickname;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function nickname(): string
    {
        return $this->nickname;
    }
}
