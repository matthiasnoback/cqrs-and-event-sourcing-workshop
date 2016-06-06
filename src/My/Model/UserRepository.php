<?php

namespace My\Model;

use Rhumsaa\Uuid\Uuid;

interface UserRepository
{
    /**
     * @param User $user
     * @return void
     */
    public function add(User $user);

    /**
     * @param Uuid $uuid
     * @return User
     */
    public function get(Uuid $uuid);
}
