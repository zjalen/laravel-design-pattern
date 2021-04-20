<?php


namespace App\Services;

use App\Models\User;

/**
 * Interface UserServiceInterface
 * @package App\Services
 */
interface UserServiceInterface
{
    /**
     * @param string $name
     * @return User|null
     */
    public function getUserInfoByName(string $name): ?User;

    /**
     * @param int $id
     * @param string $oldPassword
     * @param string $newPassword
     * @return bool
     */
    public function resetUserPassword(int $id, string $oldPassword, string $newPassword): bool;
}
