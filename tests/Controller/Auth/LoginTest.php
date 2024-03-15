<?php

namespace App\Tests\Controller\Auth;

use App\Tests\Base\AbstractTest;
use function json_encode;

class LoginTest extends AbstractTest
{
    public function testCanLogin(): void
    {
        $this->markTestSkipped('TODO');
        $this->post(
            uri: '/api/auth/login',
            content: json_encode([
                'username' => 'admin@admin.fr',
                'password' => 'admin',
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseIsSuccessful();
    }
}
