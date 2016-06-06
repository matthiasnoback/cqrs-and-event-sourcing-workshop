<?php

namespace My\Model;

use Prooph\EventSourcing\AggregateChanged;

class UserWasCreated extends AggregateChanged
{
    /**
     * @return string
     */
    public function username()
    {
        return $this->payload['name'];
    }
}
