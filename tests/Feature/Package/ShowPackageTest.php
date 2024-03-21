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

class ShowPackageTest extends AbstractTest
{
    use Factories;

    public function testCanShowPackageOfAProfileUserOwn(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $package = PackageFactory::createOne(['profile' => ProfileFactory::createOne(['user' => $user])]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonGet(
            uri: "/api/packages/{$package->getId()}",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCannotShowPackageOfAProfileUserDontOwn(): void
    {
        // Arrange & pre-assert
        $package = PackageFactory::createOne(['profile' => ProfileFactory::createOne(['user' => UserFactory::createOne()])]);
        $notTheOwner = UserFactory::createOne();

        $this->client->loginUser($notTheOwner->object());

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/packages/{$package->getId()}",
        );
    }

    public function testCannotShowPackageWhenGuest(): void
    {
        // Arrange & pre-assert
        $package = PackageFactory::createOne(['profile' => ProfileFactory::createOne(['user' => UserFactory::createOne()])]);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/packages/{$package->getId()}",
        );
    }

    public function testCannotShowNonExistentPackage(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $package = PackageFactory::createOne(['profile' => ProfileFactory::createOne(['user' => $user])]);
        $packageId = $package->getId();
        $package->remove();

        $this->client->loginUser($user->object());

        $this->expectException(NotFoundHttpException::class);

        // Act
        $this->jsonGet(
            uri: "/api/packages/{$packageId}",
        );
    }
}
