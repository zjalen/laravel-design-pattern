<?php


namespace App\Repositories;

use App\Models\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Repositories
 */
interface UserRepositoryInterface
{
    /**
     * 使用 id 查询用户模型
     * @param int $id 用户 id
     * @return User|null
     */
    public function findUserById(int $id): ?User;

    /**
     * @param int $id
     * @param string $password
     * @return User|null
     */
    public function findUserByIdAndPassword(int $id, string $password): ?User;

    /**
     * 使用 name 查询用户模型
     * @param string $name
     * @return ?User
     */
    public function getUserByName(string $name) : ?User;

    /**
     * @param User $user
     * @param string $newPassword
     * @return bool
     */
    public function resetUserPassword(User $user, string $newPassword): bool;
}
