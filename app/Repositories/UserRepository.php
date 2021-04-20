<?php
//declare(strict_types = 1);

namespace App\Repositories;


use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function getUserByName(string $name): ?User
    {
        return User::where('name', $name)->first();
    }

    public function resetUserPassword(User $user, string $newPassword): bool
    {
        $user->password = $newPassword;
        return $user->save();
    }

    public function findUserById(int $id): ?User
    {
        return User::find($id);
    }

    public function findUserByIdAndPassword(int $id, string $password): ?User
    {
        return User::whereId($id)->where('password', $password)->first();
    }
}
