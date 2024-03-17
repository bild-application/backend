<?php

namespace App\Tests\Controller\Auth;

use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use function json_encode;
use const JSON_THROW_ON_ERROR;

class RegisterTest extends AbstractTest
{
    public function testCanRegister(): void
    {
        $this->jsonPost(
            uri: '/api/auth/register',
            content: [
                'email' => 'user@email.com',
                'password' => [
                    'first' => 'password',
                    'second' => 'password',
                ],
                'agreeTerms' => true,
            ],
        );

        self::assertResponseIsSuccessful();
    }

    public function testReturnErrorIfInvalidEmail(): void
    {
        $this->jsonPost(
            uri: '/api/auth/register',
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

    public function testReturnErrorIfAlreadyTakenEmail(): void
    {
        $this->markTestSkipped('TODO');

        //todo generate a user with same email in bdd

        $this->jsonPost(
            uri: '/api/auth/register',
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

    public function testReturnErrorIfNoPassword(): void
    {
        $this->jsonPost(
            uri: '/api/auth/register',
            content: [
                'email' => 'user@email',
                'agreeTerms' => true,
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testReturnErrorIfNotSamePassword(): void
    {
        $this->jsonPost(
            uri: '/api/auth/register',
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

    public function testReturnErrorIfTooShortPassword(): void
    {
        $this->jsonPost(
            uri: '/api/auth/register',
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

    public function testReturnErrorIfTermsAreNotAccepted(): void
    {
        $this->jsonPost(
            uri: '/api/auth/register',
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
