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
        $this->post(
            uri: '/api/auth/register',
            content: json_encode([
                'email' => 'user@email.com',
                'password' => [
                    'first' => 'password',
                    'second' => 'password',
                ],
                'agreeTerms' => true,
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseIsSuccessful();
    }

    public function testReturnErrorIfInvalidEmail(): void
    {
        $this->post(
            uri: '/api/auth/register',
            content: json_encode([
                'email' => 'user@email',
                'password' => [
                    'first' => 'password',
                    'second' => 'password',
                ],
                'agreeTerms' => true,
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testReturnErrorIfAlreadyTakenEmail(): void
    {
        $this->markTestSkipped('TODO');

        //todo generate a user with same email in bdd

        $this->post(
            uri: '/api/auth/register',
            content: json_encode([
                'email' => 'user@email',
                'password' => [
                    'first' => 'password',
                    'second' => 'password',
                ],
                'agreeTerms' => true,
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testReturnErrorIfNoPassword(): void
    {
        $this->post(
            uri: '/api/auth/register',
            content: json_encode([
                'email' => 'user@email',
                'agreeTerms' => true,
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testReturnErrorIfNotSamePassword(): void
    {
        $this->post(
            uri: '/api/auth/register',
            content: json_encode([
                'email' => 'user@email',
                'password' => [
                    'first' => 'password1',
                    'second' => 'password2',
                ],
                'agreeTerms' => true,
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testReturnErrorIfTooShortPassword(): void
    {
        $this->post(
            uri: '/api/auth/register',
            content: json_encode([
                'email' => 'user@email',
                'password' => [
                    'first' => 'pass',
                    'second' => 'pass',
                ],
                'agreeTerms' => true,
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testReturnErrorIfTermsAreNotAccepted(): void
    {
        $this->post(
            uri: '/api/auth/register',
            content: json_encode([
                'email' => 'user@email',
                'password' => [
                    'first' => 'pass',
                    'second' => 'pass',
                ],
                'agreeTerms' => false,
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
