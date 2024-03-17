<?php

namespace App\Tests\Controller\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $this->jsonDelete(
            uri: "/api/profiles/{$profile->getId()}",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
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
        $this->expectException(NotFoundHttpException::class);

        try {
            $this->jsonDelete(
                uri: "/api/profiles/",
            );
        } catch (NotFoundHttpException $e) {
            // Assert
            ProfileFactory::assert()->count(1);
            UserFactory::assert()->count(1);

            throw $e;
        }
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

        $this->expectException(NotFoundHttpException::class);

        // Act
        try {
            $this->jsonDelete(
                uri: "/api/profiles/{$profileId}",
            );
        } catch (NotFoundHttpException $e) {
            // Assert
            ProfileFactory::assert()->count(0);
            UserFactory::assert()->count(1);

            throw $e;
        }
    }

    public function testCannotDeleteProfileUserDontOwn(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $notTheOwner = UserFactory::createOne()->object();
        $this->client->loginUser($notTheOwner);

        UserFactory::assert()->count(2);
        ProfileFactory::assert()->count(1);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonDelete(
                uri: "/api/profiles/{$profile->getId()}",
            );
        } catch (AccessDeniedException $e) {
            // Assert
            ProfileFactory::assert()->count(1);

            throw $e;
        }
    }

    public function testCannotDeleteWhenGuest(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(1);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonDelete(
                uri: "/api/profiles/{$profile->getId()}",
            );
        } catch (AccessDeniedException $e) {
            // Assert
            ProfileFactory::assert()->count(1);

            throw $e;
        }
    }
}
