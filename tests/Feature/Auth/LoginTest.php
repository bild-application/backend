<?php

namespace App\Tests\Feature\Auth;

use App\Tests\Base\AbstractTest;

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
