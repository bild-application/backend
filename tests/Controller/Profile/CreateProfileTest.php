<?php

namespace App\Tests\Controller\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

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
        $this->jsonPost(
            uri: '/api/profiles',
            content: [
                'name' => 'Paul',
            ],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
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
        $this->jsonPost(
            uri: '/api/profiles',
            content: [],
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
        try {
            $this->jsonPost(
                uri: '/api/profiles',
                content: [
                    'name' => 'Paul',
                ],
            );
        } catch (AccessDeniedException $e) {
            // Assert
            ProfileFactory::assert()->count(0);

            throw $e;
        }
    }
}
