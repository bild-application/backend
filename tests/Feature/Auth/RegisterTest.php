<?php

namespace App\Tests\Feature\Auth;

use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class RegisterTest extends AbstractTest
{
    public function test_can_register(): void
    {
        $this->jsonPost(
            uri: '/api/public/register',
            content: [
                'email' => 'user@email.com',
                'password' => [
                    'first' => 'password',
                    'second' => 'password',
                ],
                'agreeTerms' => true,
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function test_return_error_if_invalid_email(): void
    {
        $this->jsonPost(
            uri: '/api/public/register',
            content: [
                'email' => 'user@email',
                'password' => [
                    'first' => 'password',
                    'second' => 'password',
                ],
                'agreeTerms' => true,
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_return_error_if_already_taken_email(): void
    {
        UserFactory::createOne([
            'email' => 'user@email.com',
        ]);

        $this->jsonPost(
            uri: '/api/public/register',
            content: [
                'email' => 'user@email.com',
                'password' => [
                    'first' => 'password',
                    'second' => 'password',
                ],
                'agreeTerms' => true,
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_return_error_if_no_password(): void
    {
        $this->jsonPost(
            uri: '/api/public/register',
            content: [
                'email' => 'user@email',
                'agreeTerms' => true,
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_return_error_if_not_same_password(): void
    {
        $this->jsonPost(
            uri: '/api/public/register',
            content: [
                'email' => 'user@email',
                'password' => [
                    'first' => 'password1',
                    'second' => 'password2',
                ],
                'agreeTerms' => true,
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_return_error_if_too_short_password(): void
    {
        $this->jsonPost(
            uri: '/api/public/register',
            content: [
                'email' => 'user@email',
                'password' => [
                    'first' => 'pass',
                    'second' => 'pass',
                ],
                'agreeTerms' => true,
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_return_error_if_terms_are_not_accepted(): void
    {
        $this->jsonPost(
            uri: '/api/public/register',
            content: [
                'email' => 'user@email',
                'password' => [
                    'first' => 'pass',
                    'second' => 'pass',
                ],
                'agreeTerms' => false,
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
