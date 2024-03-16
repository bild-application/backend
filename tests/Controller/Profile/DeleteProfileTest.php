<?php

namespace App\Tests\Controller\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class DeleteProfileTest extends AbstractTest
{
    use Factories;

    public function testCanDeleteProfileUserOwn(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $profile->getUser();

        ProfileFactory::assert()->count(1);
        UserFactory::assert()->count(1);

        $this->client->loginUser($user);

        // Act
        $this->delete(
            uri: "/api/profiles/{$profile->getId()}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseIsSuccessful();
        ProfileFactory::assert()->count(0);
        UserFactory::assert()->count(1);
    }

    public function testCannotDeleteWithoutId(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $profile->getUser();

        ProfileFactory::assert()->count(1);
        UserFactory::assert()->count(1);

        $this->client->loginUser($user);

        // Act
        $this->delete(
            uri: "/api/profiles/{$profile->getId()}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        ProfileFactory::assert()->count(1);
        UserFactory::assert()->count(1);
    }

    public function testCannotDeleteNonExistentId(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $profile->getUser();
        $profileId = $profile->getId();
        $profile->remove();

        ProfileFactory::assert()->count(0);
        UserFactory::assert()->count(1);

        $this->client->loginUser($user);

        // Act
        $this->delete(
            uri: "/api/profiles/{$profileId}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        ProfileFactory::assert()->count(0);
        UserFactory::assert()->count(1);
    }

    public function testCannotDeleteProfileUserDontOwn(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $notTheOwner = UserFactory::createOne()->object();
        $this->client->loginUser($notTheOwner);

        UserFactory::assert()->count(2);
        ProfileFactory::assert()->count(1);

        // Act
        $this->delete(
            uri: "/api/profiles/{$profile->getId()}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        ProfileFactory::assert()->count(1);
    }

    public function testCannotDeleteWhenGuest(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(1);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->delete(
            uri: "/api/profiles/{$profile->getId()}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        ProfileFactory::assert()->count(1);
    }
}
