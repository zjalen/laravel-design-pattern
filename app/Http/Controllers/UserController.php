<?php
declare(strict_types=1);


namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserServiceInterface;

class UserController extends BaseApiController
{
    /**
     * @var UserServiceInterface
     */
    private $userService;

    public function __construct()
    {
        $this->userService = app(UserServiceInterface::class);
    }

    public function getUserByName(UserRequest $request)
    {
        $name = $request->input('name');
        $user = $this->userService->getUserInfoByName($name);
        return $this->success($user);
    }

    public function resetPassword(UserRequest $request): string
    {
        $id = $request->input('id');
        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');
        $bool = $this->userService->resetUserPassword((int) $id, $oldPassword, $newPassword);
        if ($bool) {
            return $this->success();
        } else {
            return $this->fail('修改失败');
        }
    }
}
