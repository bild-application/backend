<?php

namespace App\Tests\Controller\Auth;

use App\Tests\Base\AbstractTest;
use function json_encode;

class RegisterTest extends AbstractTest
{
    public function testCanRegister(): void
    {
        $this->post(
            uri: '/api/auth/register',
            content: json_encode([
                'username' => 'user@email.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseIsSuccessful();
    }
}
