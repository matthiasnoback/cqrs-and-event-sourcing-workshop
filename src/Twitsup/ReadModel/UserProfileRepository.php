<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

use Common\Persistence\Database;

final class UserProfileRepository
{
    public function save(UserProfile $userProfile): void
    {
        Database::persist($userProfile);
    }

    public function getByUserId(string $userId): UserProfile
    {
        foreach (Database::retrieveAll(UserProfile::class) as $userProfile) {
            /** @var UserProfile $userProfile */
            if ($userProfile->id == $userId) {
                return $userProfile;
            }
        }

        throw new \RuntimeException('User not found');
    }

    public function reset(): void
    {
        Database::deleteAll(UserProfile::class);
    }
}
