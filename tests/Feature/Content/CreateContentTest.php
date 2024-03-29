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

    public function test_can_create_content_for_user(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user->object());

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

    public function test_can_create_content_in_user_profile(): void
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

    public function test_cannot_create_content_in_other_user_profile(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
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

    public function test_cannot_create_content_without_name(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user->object());

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

    public function test_cannot_create_content_without_image(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user->object());

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

    public function test_cannot_create_content_with_image_other_than_png_or_jpg(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();

        ContentFactory::assert()->count(0);

        $this->client->loginUser($user->object());

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

    public function test_cannot_create_content_when_guest(): void
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
