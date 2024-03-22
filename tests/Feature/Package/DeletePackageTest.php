<?php

namespace App\Tests\Feature\Package;

use App\Factory\PackageFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class DeletePackageTest extends AbstractTest
{
    use Factories;

    public function test_can_delete_package(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        $package = PackageFactory::createOne(['profile' => $profile]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonDelete("/api/packages/{$package->getId()}");

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        PackageFactory::assert()->count(0);
    }

    public function test_cannot_delete_package_user_dont_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        $package = PackageFactory::createOne(['profile' => $profile]);
        $notTheOwner = UserFactory::createOne();

        $this->client->loginUser($notTheOwner->object());

        // Assert
        $this->expectException(AccessDeniedException::class);

        try {
            // Act
            $this->jsonDelete("/api/packages/{$package->getId()}");
        } catch (AccessDeniedException $e) {
            // Assert
            PackageFactory::assert()->count(1);

            throw  $e;
        }
    }
}
