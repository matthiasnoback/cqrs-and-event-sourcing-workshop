<?php
declare(strict_types=1);

namespace Twitsup\ReadModel;

use Common\Persistence\Database;

final class UserLookupRepository
{
    private $repository;

    public function save(User $user): void
    {
        Database::persist($user);
    }

    public function getUserIdForUsername(string $username): string
    {
        foreach (Database::retrieveAll(User::class) as $user) {
            /** @var User $user */
            if ($user->username == $username) {
                return $user->id;
            }
        }

        throw new \RuntimeException('User not found');
    }

    public function userWithUsernameExists(string $username): bool
    {
        foreach (Database::retrieveAll(User::class) as $user) {
            /** @var User $user */
            if ($user->username == $username) {
                return true;
            }
        }

        return false;
    }

    public function reset(): void
    {
        Database::deleteAll(User::class);
    }
}
