<?php

namespace App\Tests\Feature\Content;

use App\Factory\ContentFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class CreateProfileContentTest extends AbstractTest
{
    use Factories;

    public function testCanCreateContentInProfileUserOwn(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: "/api/profiles/{$profile->getId()}/contents",
            content: [
                'name' => 'Chat',
            ],
            files: [
                'image' => new UploadedFile(
                    __DIR__ . '/../../Stub/placeholder.jpg',
                    'placeholder.jpg'
                ),
            ]
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        ContentFactory::assert()->count(1);
        self::assertFsFileExists(ContentFactory::first()->getImage());
    }

    public function testCannotCreateWithOtherUserProfileAsOwner(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $notTheOwner = UserFactory::createOne();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($notTheOwner->object());

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonPost(
                uri: "/api/profiles/{$profile->getId()}/contents",
                content: [
                    'name' => 'Chat',
                ],
                files: [
                    'image' => new UploadedFile(
                        __DIR__ . '/../../Stub/placeholder.jpg',
                        'placeholder.jpg'
                    ),
                ]
            );
        } catch (AccessDeniedException $e) {
            ContentFactory::assert()->count(0);

            throw $e;
        }
    }

    public function testCannotCreateWithoutName(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: "/api/profiles/{$profile->getId()}/contents",
            files: [
                'image' => new UploadedFile(
                    __DIR__ . '/../../Stub/placeholder.jpg',
                    'placeholder.jpg'
                ),
            ]
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        ContentFactory::assert()->count(0);
    }

    public function testCannotCreateWithoutImage(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: "/api/profiles/{$profile->getId()}/contents",
            content: [
                'name' => 'Chat',
            ],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        ContentFactory::assert()->count(0);
    }

    public function testCannotCreateWithOtherThanPngOrJpgAsImage(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: "/api/profiles/{$profile->getId()}/contents",
            content: [
                'name' => 'Chat',
            ],
            files: [
                'image' => new UploadedFile(
                    __DIR__ . '/../../Stub/placeholder.txt',
                    'placeholder.txt'
                ),
            ]
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        ContentFactory::assert()->count(0);
    }

    public function testCannotCreateWhenGuest(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);

        ContentFactory::assert()->count(0);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonPost(
                uri: "/api/profiles/{$profile->getId()}/contents",
                content: [
                    'name' => 'Chat',
                ],
                files: [
                    'image' => new UploadedFile(
                        __DIR__ . '/../../Stub/placeholder.jpg',
                        'placeholder.jpg'
                    ),
                ]
            );
        } catch (AccessDeniedException $e) {
            ContentFactory::assert()->count(0);

            throw $e;
        }
    }
}
