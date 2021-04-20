<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function testGetUserByName()
    {
        User::factory(1)->create();
        $user = $this->userRepository->getUserByName("Tom");
        self::assertInstanceOf(User::class, $user);
        $user = $this->userRepository->getUserByName("456");
        self::assertNull($user);
    }
}
