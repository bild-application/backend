<?php

namespace App\Tests\Controller\Package;

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

    public function testCanListPackagesUserOwn(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        PackageFactory::createMany(4, ['profile' => ProfileFactory::createOne(['user' => $user])]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonGet(
            uri: "/api/packages/",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertCount(4, $json);
    }

    public function testCannotListPackagesUserDontOwn(): void
    {
        // Arrange & pre-assert
        PackageFactory::createMany(4, ['profile' => ProfileFactory::createOne(['user' => UserFactory::createOne()])]);
        $notTheOwner = UserFactory::createOne();

        $this->client->loginUser($notTheOwner->object());

        // Act
        $this->jsonGet(
            uri: "/api/packages/",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertCount(0, $json);
    }

    public function testCannotListWhenGuest(): void
    {
        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/packages/",
        );
    }

    public function testCanListOnlyPackagesOfProfileUserOwn(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        PackageFactory::createMany(4, ['profile' => $profile]);
        PackageFactory::createMany(1, ['profile' => ProfileFactory::createOne(['user' => $user])]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonGet(
            uri: "/api/packages/?profile={$profile->getId()}",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertCount(4, $json);
    }

    public function testCannotListPackagesOfProfileUserDontOwn(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        PackageFactory::createMany(4, ['profile' => $profile]);
        $notTheOwner = UserFactory::createOne();

        $this->client->loginUser($notTheOwner->object());

        // Act
        $this->jsonGet(
            uri: "/api/packages/?profile={$profile->getId()}",
        );
        $json = $this->jsonResponseContent();
        self::assertCount(0, $json);
    }
}
