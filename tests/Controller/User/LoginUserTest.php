<?php

namespace App\Tests\Controller\User;

use App\Tests\Base\AbstractTest;
use function json_encode;

class LoginUserTest extends AbstractTest
{
    public function testLogin(): void
    {
        $this->post(
            uri: '/api/login',
            content: json_encode([
                'username' => 'admin@admin.fr',
                'password' => 'admin',
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseIsSuccessful();
    }
}
