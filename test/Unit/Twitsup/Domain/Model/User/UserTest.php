<?php
declare(strict_types=1);

namespace Test\Unit\Twitsup\Domain\Model\User;

use Twitsup\Domain\Model\User\User;
use Twitsup\Domain\Model\User\UserId;
use Twitsup\Domain\Model\User\UserRegistered;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function a_user_can_be_registered()
    {
        $id = UserId::fromString('018da022-1dd0-47cb-8701-e65f3b074817');
        $username = 'matthiasnoback';
        $nickname = 'Matthias Noback';
        $user = User::register($id, $username, $nickname);

        $this->assertEquals([
            new UserRegistered($id, $username, $nickname)
        ], $user->popRecordedEvents());
    }
}
