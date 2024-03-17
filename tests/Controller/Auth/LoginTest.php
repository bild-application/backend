<?php

namespace App\Tests\Controller\Auth;

use App\Tests\Base\AbstractTest;
use function json_encode;

class LoginTest extends AbstractTest
{
    public function testCanLogin(): void
    {
        $this->markTestSkipped('TODO');
        $this->jsonPost(
            uri: '/api/auth/login',
            content: [
                'username' => 'admin@admin.fr',
                'password' => 'admin',
            ],
        );

        self::assertResponseIsSuccessful();
    }
}
