<?php

namespace App\Tests\Feature\Package;

use App\Factory\PackageFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class ListPackageTest extends AbstractTest
{
    use Factories;

    public function test_can_list_packages_user_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        PackageFactory::createMany(4, ['profile' => $profile]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonGet(uri: "/api/profiles/{$profile->getId()}/packages");

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertCount(4, $json);
    }

    public function test_cannot_list_packages_user_dont_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        $notTheOwner = UserFactory::createOne();

        PackageFactory::createMany(4, ['profile' => $profile]);

        $this->client->loginUser($notTheOwner->object());

        // Assert
        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(uri: "/api/profiles/{$profile->getId()}/packages");
    }

    public function test_cannot_list_when_guest(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        // Assert
        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(uri: "/api/profiles/{$profile->getId()}/packages");
    }

    public function test_can_list_only_packages_of_profile_user_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        PackageFactory::createMany(4, ['profile' => $profile]);
        PackageFactory::createMany(2, [
            'profile' => ProfileFactory::createOne(['user' => $user]),
        ]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonGet(uri: "/api/profiles/{$profile->getId()}/packages");

        // Assert
        $json = $this->jsonResponseContent();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertCount(4, $json);
    }
}
