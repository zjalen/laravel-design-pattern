<?php
namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 继承自 Tests\TestCase 可以使用 laravel 提供的容器方法等
 * Class UserServiceTest
 * @package Tests\Unit\Services
 */
class UserServiceTest extends TestCase
{
    // 保证测试结束数据库重置
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = app(UserServiceInterface::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @var UserServiceInterface
     */
    private $userService;

    public function testGetUserInfoByName()
    {
        User::factory(1)->create();
        $user = $this->userService->getUserInfoByName("Tom");
        self::assertInstanceOf(User::class, $user);
        $user = $this->userService->getUserInfoByName("456");
        self::assertNull($user);
    }

    public function testResetUserPasswordRight()
    {
        User::factory(1)->create();
        $user = User::first();
        $bool = $this->userService->resetUserPassword($user->id, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123');
        self::assertTrue($bool);
    }

    public function testResetUserPasswordWrongId()
    {
        $this->expectExceptionCode(404);
        $this->userService->resetUserPassword(1000, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123');
    }

    public function testResetUserPasswordWrongPassword()
    {
        User::factory(1)->create();
        $this->expectExceptionCode(404);
        $this->userService->resetUserPassword(1, '8899', '234');
    }
}
