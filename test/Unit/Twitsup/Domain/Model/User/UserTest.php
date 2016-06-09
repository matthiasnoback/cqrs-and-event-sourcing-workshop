<?php

namespace Test\Unit\Twitsup\Domain\Model\User;

use EventSourcing\Aggregate\Testing\RecordedEventsEqual;
use Ramsey\Uuid\Uuid;
use Twitsup\Domain\Model\User\User;
use Twitsup\Domain\Model\User\UserRegistered;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function a_user_can_be_registered()
    {
        $id = Uuid::uuid4();
        $username = 'matthiasnoback';
        $nickname = 'Matthias Noback';
        $user = User::register($id, $username, $nickname);

        self::assertThat([
            new UserRegistered($id, $username, $nickname)
        ], new RecordedEventsEqual($user));
    }
}
