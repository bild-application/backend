<?php

namespace App\Tests\Feature\Package;

use App\Factory\PackageFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class CreatePackageTest extends AbstractTest
{
    use Factories;

    public function testCanCreateInAProfileUserOwn(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        PackageFactory::assert()->count(0);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: '/api/packages',
            content: [
                'name' => 'Animaux',
                'profile' => $profile->getId(),
            ],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        PackageFactory::assert()->count(1);
    }

    public function testCannotCreateInAProfileUserDontOwn(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $notTheOwner = UserFactory::createOne();

        PackageFactory::assert()->count(0);

        $this->client->loginUser($notTheOwner->object());

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonPost(
                uri: '/api/packages',
                content: [
                    'name' => 'Animaux',
                    'profile' => $profile->getId(),
                ],
            );
        } catch (AccessDeniedException $e) {
            // Assert
            PackageFactory::assert()->count(0);

            throw  $e;
        }
    }

    public function testCannotCreateWhenGuest(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);

        PackageFactory::assert()->count(0);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonPost(
                uri: '/api/packages',
                content: [
                    'name' => 'Animaux',
                    'profile' => $profile->getId(),
                ],
            );
        } catch (AccessDeniedException $e) {
            // Assert
            PackageFactory::assert()->count(0);

            throw  $e;
        }
    }

    public function testCannotCreateWithoutName(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        PackageFactory::assert()->count(0);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: '/api/packages',
            content: [
                'profile' => $profile->getId(),
            ],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        PackageFactory::assert()->count(0);
    }

    public function testCannotCreateWithoutProfile(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        PackageFactory::assert()->count(0);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: '/api/packages',
            content: [
                'name' => 'Animaux',
            ],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        PackageFactory::assert()->count(0);
    }

    public function testCannotCreateForNonExistentProfile(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        $profileId = $profile->getId();
        $profile->remove();

        ProfileFactory::assert()->count(0);
        PackageFactory::assert()->count(0);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: '/api/packages',
            content: [
                'name' => 'Animaux',
                'profile' => $profileId,
            ],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        PackageFactory::assert()->count(0);
    }
}
