<?php

namespace App\Tests\Feature\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class UpdateProfileTest extends AbstractTest
{
    use Factories;

    public function test_can_update_a_profile_user_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        self::assertNotEquals('Paul', $profile->getName());

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPut(
            uri: "/api/profiles/{$profile->getId()}",
            content: [
                'name' => 'Paul',
            ],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        ProfileFactory::assert()->count(1);
        $profile->refresh();
        self::assertEquals('Paul', $profile->getName());
    }

    public function test_cannot_update_profile_without_name(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPut(
            uri: "/api/profiles/{$profile->getId()}",
            content: [],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        ProfileFactory::assert()->count(1);
    }

    public function test_cannot_update_profile_user_dont_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        $notTheOwner = UserFactory::createOne();
        $this->client->loginUser($notTheOwner->object());

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonPut(
                uri: "/api/profiles/{$profile->getId()}",
                content: [
                    'name' => 'Paul',
                ],
            );
        } catch (AccessDeniedException $e) {
            // Assert
            self::assertNotEquals('Paul', $profile->getName());

            throw $e;
        }
    }

    public function test_cannot_update_profile_when_guest(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonPut(
                uri: "/api/profiles/{$profile->getId()}",
                content: [
                    'name' => 'Paul',
                ],
            );
        } catch (AccessDeniedException $e) {
            // Assert
            self::assertNotEquals('Paul', $profile->getName());

            throw $e;
        }
    }
}
