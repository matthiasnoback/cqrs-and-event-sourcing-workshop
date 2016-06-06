<?php

namespace My\Model;

use Prooph\EventSourcing\AggregateRoot;
use Rhumsaa\Uuid\Uuid;

class User extends AggregateRoot
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @var string
     */
    private $name;

    /**
     * ARs should be created via static factory methods
     *
     * @param string $username
     * @return User
     */
    public static function nameNew($username)
    {
        //Perform assertions before raising a event
        \Assert\that($username)->notEmpty()->string();

        $uuid = Uuid::uuid4();

        //AggregateRoot::__construct is defined as protected so it can be called in a static factory of
        //an extending class
        $instance = new self();

        //Use AggregateRoot::recordThat method to apply a new Event
        $instance->recordThat(UserWasCreated::occur($uuid->toString(), ['name' => $username]));

        return $instance;
    }

    /**
     * @return Uuid
     */
    public function userId()
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param $newName
     */
    public function changeName($newName)
    {
        \Assert\that($newName)->notEmpty()->string();

        if ($newName != $this->name) {
            $this->recordThat(UserWasRenamed::occur(
                $this->uuid->toString(),
                ['new_name' => $newName, 'old_name' => $this->name]
            ));
        }
    }

    /**
     * Each applied event needs a corresponding handler method.
     *
     * The naming convention is: when[:ShortEventName]
     *
     * @param UserWasCreated $event
     */
    protected function whenUserWasCreated(UserWasCreated $event)
    {
        //Simply assign the event payload to the appropriate properties
        $this->uuid = Uuid::fromString($event->aggregateId());
        $this->name = $event->username();
    }

    /**
     * @param UserWasRenamed $event
     */
    protected function whenUserWasRenamed(UserWasRenamed $event)
    {
        $this->name = $event->newName();
    }

    /**
     * Every AR needs a hidden method that returns the identifier of the AR as a string
     *
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->uuid->toString();
    }
}
