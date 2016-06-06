<?php

namespace My\Model;

use Prooph\EventSourcing\AggregateChanged;

class UserWasRenamed extends AggregateChanged
{
    /**
     * @return string
     */
    public function newName()
    {
        return $this->payload['new_name'];
    }

    /**
     * @return string
     */
    public function oldName()
    {
        return $this->payload['old_name'];
    }
}
