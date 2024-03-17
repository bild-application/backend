<?php

namespace App\Tests\Controller\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
        $this->jsonGet(
            uri: "/api/profiles/{$profile->getId()}",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCannotSeeNonExistentId(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $profile->getUser();
        $profileId = $profile->getId();
        $profile->remove();

        ProfileFactory::assert()->count(0);
        UserFactory::assert()->count(1);

        $this->client->loginUser($user);

        $this->expectException(NotFoundHttpException::class);

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profileId}",
        );
    }

    public function testCannotSeeProfileUserDontOwn(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $notTheOwner = UserFactory::createOne()->object();

        UserFactory::assert()->count(2);

        $this->client->loginUser($notTheOwner);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profile->getId()}",
        );
    }

    public function testCannotSeeProfileWhenGuest(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profile->getId()}",
        );
    }
}
