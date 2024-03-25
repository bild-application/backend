<?php

namespace App\Tests\Feature\Package;

use App\Factory\PackageFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class UpdatePackageTest extends AbstractTest
{
    use Factories;

    public function test_can_update_package(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        $package = PackageFactory::createOne(['profile' => $profile]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPatch(
            uri: "/api/packages/{$package->getid()}",
            content: ['name' => 'new name']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        PackageFactory::assert()->count(1);

        $package->refresh();
        self::assertEquals('new name', $package->getName());
    }

    public function test_cannot_update_package_user_dont_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        $package = PackageFactory::createOne(['profile' => $profile]);
        $notTheOwner = UserFactory::createOne();

        $this->client->loginUser($notTheOwner->object());

        // Assert
        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonPatch(
            uri: "/api/packages/{$package->getid()}",
            content: ['name' => 'new name']
        );
    }
}
