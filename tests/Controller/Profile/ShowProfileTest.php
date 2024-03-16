<?php

namespace App\Tests\Controller\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

class ShowProfileTest extends AbstractTest
{
    use Factories;

    public function testCanSeeProfileUserOwn(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $profile->getUser();

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(1);

        $this->client->loginUser($user);

        // Act
        $this->get(
            uri: "/api/profile/{$profile->getId()}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseIsSuccessful();
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
        $this->get(
            uri: "/api/profiles/{$profileId}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        ProfileFactory::assert()->count(0);
        UserFactory::assert()->count(1);
    }

    public function testCannotSeeProfileUserDontOwn(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $notTheOwner = UserFactory::createOne()->object();

        UserFactory::assert()->count(2);

        $this->client->loginUser($notTheOwner);

        // Act
        $this->get(
            uri: "/api/profiles/{$profile->getId()}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCannotListWhenGuest(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);

        // Act
        $this->get(
            uri: "/api/profiles/{$profile->getId()}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
