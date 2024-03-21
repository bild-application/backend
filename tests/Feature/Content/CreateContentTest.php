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

class CreateContentTest extends AbstractTest
{
    use Factories;

    public function test_can_create_with_itself_as_owner(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->jsonPost(
            uri: "/api/contents",
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

    public function test_can_create_with_their_profile_as_owner(): void
    {
        // Arrange & pre-assert
        $userProxy = UserFactory::createOne();
        $profileProxy = ProfileFactory::createOne(['user' => $userProxy]);

        ContentFactory::assert()->count(0);

        $this->client->loginUser($userProxy->object());

        // Act
        $this->jsonPost(
            uri: "/api/contents",
            content: [
                'name' => 'Chat',
                'profile' => $profileProxy->getId(),
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

    public function test_cannot_create_with_other_user_profile_as_owner(): void
    {
        // Arrange & pre-assert
        $profileProxy = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $otherUserProfileProxy = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $profileProxy->getUser();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonPost(
                uri: "/api/contents",
                content: [
                    'name' => 'Chat',
                    'profile' => $otherUserProfileProxy->getId(),
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

    public function test_cannot_create_without_name(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->jsonPost(
            uri: "/api/contents",
            content: [],
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

    public function test_cannot_create_without_image(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->jsonPost(
            uri: "/api/contents",
            content: [
                'name' => 'Chat',
            ],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        ContentFactory::assert()->count(0);
    }

    public function test_cannot_create_with_other_than_png_or_jpg_as_image(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->jsonPost(
            uri: "/api/contents",
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

    public function test_cannot_create_when_guest(): void
    {
        // Arrange & pre-assert
        ContentFactory::assert()->count(0);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonPost(
                uri: "/api/contents",
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
