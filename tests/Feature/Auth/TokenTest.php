<?php

namespace App\Tests\Feature\Auth;

use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TokenTest extends AbstractTest
{
    public function test_can_get_token(): void
    {
        UserFactory::createOne([
            'email' => 'user@email.com',
            'password' => 'password',
        ]);
        $this->jsonPost(
            uri: '/api/public/token',
            content: [
                'username' => 'user@email.com',
                'password' => 'password',
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertArrayHasKey('token', $json);
    }

    public function test_cannot_get_token_with_invalid_credentials(): void
    {
        UserFactory::createOne([
            'email' => 'user@email.com',
            'password' => 'password',
        ]);
        $this->jsonPost(
            uri: '/api/public/token',
            content: [
                'username' => 'user@email.com',
                'password' => 'password2222',
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function test_cannot_get_token_without_username(): void
    {
        UserFactory::createOne([
            'email' => 'user@email.com',
            'password' => 'password',
        ]);

        $this->expectException(BadRequestHttpException::class);
        $this->jsonPost(
            uri: '/api/public/token',
            content: [
                'password' => 'password',
            ],
        );
    }

    public function test_cannot_get_token_without_password(): void
    {
        UserFactory::createOne([
            'email' => 'user@email.com',
            'password' => 'password',
        ]);

        $this->expectException(BadRequestHttpException::class);
        $this->jsonPost(
            uri: '/api/public/token',
            content: [
                'username' => 'user@email.com',
            ],
        );
    }
}
