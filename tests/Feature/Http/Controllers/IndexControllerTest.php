<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function testResetPassword()
    {
        $response = $this->get('/index/resetPassword');
        $response->assertStatus(400);
    }

    public function testIndex()
    {
        $response = $this->get('/index/index');
        $response->assertOk();
    }
}
