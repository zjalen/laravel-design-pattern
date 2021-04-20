<?php
//declare(strict_types = 1);

namespace App\Services;


use App\Exceptions\BusinessExceptions\NotFoundBusinessException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Exception;

class UserService extends BaseService implements UserServiceInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function getUserInfoByName(string $name): ?User
    {
        return $this->userRepository->getUserByName($name);
    }

    /**
     * @throws Exception
     */
    public function resetUserPassword(int $id, string $oldPassword, string $newPassword): bool
    {
        $user = $this->userRepository->findUserByIdAndPassword($id, $oldPassword);
        if (!$user) {
            throw new NotFoundBusinessException('未找到用户');
        }
        return $this->userRepository->resetUserPassword($user, $newPassword);
    }
}
