<?php

namespace App\Tests\Feature\Package;

use App\Factory\PackageFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class CreatePackageTest extends AbstractTest
{
    use Factories;

    public function test_can_create_in_a_profile_user_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: "/api/profiles/{$profile->getId()}/packages",
            content: ['name' => 'Animaux']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        PackageFactory::assert()->count(1);
    }

    public function test_cannot_create_in_a_profile_user_dont_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        $notTheOwner = UserFactory::createOne();

        $this->client->loginUser($notTheOwner->object());

        $this->expectException(AccessDeniedException::class);

        try {
            // Act
            $this->jsonPost(
                uri: "/api/profiles/{$profile->getId()}/packages",
                content: ['name' => 'Animaux']
            );
        } catch (AccessDeniedException $e) {
            // Assert
            PackageFactory::assert()->count(0);

            throw  $e;
        }
    }

    public function test_cannot_create_without_name(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(uri: "/api/profiles/{$profile->getId()}/packages");

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        PackageFactory::assert()->count(0);
    }

    public function test_cannot_create_for_non_existent_profile(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        $profileId = $profile->getId();
        $profile->remove();

        $this->client->loginUser($user->object());

        // Assert
        $this->expectException(NotFoundHttpException::class);

        try {
            // Act
            $this->jsonPost(
                uri: "/api/profiles/{$profileId}/packages",
                content: ['name' => 'Animaux']
            );
        } catch (NotFoundHttpException $e) {
            // Assert
            PackageFactory::assert()->count(0);

            throw  $e;
        }
    }
}
