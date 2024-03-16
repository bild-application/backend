<?php

namespace App\Tests\Controller\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;
use function json_encode;
use const JSON_THROW_ON_ERROR;

class CreateProfileTest extends AbstractTest
{
    use Factories;

    public function testCanCreate(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->post(
            uri: '/api/profiles',
            content: json_encode([
                'name' => 'Paul',
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseIsSuccessful();
        ProfileFactory::assert()->count(1);
    }

    public function testCannotCreateWithoutName(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->post(
            uri: '/api/profiles',
            content: json_encode([
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        ProfileFactory::assert()->count(0);
    }

    public function testCannotCreateWhenGuest(): void
    {
        // Arrange & pre-assert
        ProfileFactory::assert()->count(0);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->post(
            uri: '/api/profiles',
            content: json_encode([
                'name' => 'Paul',
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        ProfileFactory::assert()->count(0);
    }
}
