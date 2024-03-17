<?php

namespace App\Tests\Controller\Content;

use App\Factory\ContentFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;
use function json_encode;
use const JSON_THROW_ON_ERROR;

class CreateContentTest extends AbstractTest
{
    use Factories;

    public function testCanCreateWithItselfAsOwner(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();

        UserFactory::assert()->count(1);
        ContentFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->post(
            uri: "/api/contents",
            content: json_encode([
                'name' => 'Chat',
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        ContentFactory::assert()->count(1);
    }

    public function testCanCreateWithTheirProfileAsOwner(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $profile->getUser();

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(1);
        ContentFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->post(
            uri: "/api/contents/{$profile->getId()}",
            content: json_encode([
                'name' => 'Chat',
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        ContentFactory::assert()->count(1);
    }

    public function testCannotCreateWithOtherUserProfileAsOwner(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $otherUserProfile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $profile->getUser();

        UserFactory::assert()->count(2);
        ProfileFactory::assert()->count(2);
        ContentFactory::assert()->count(0);

        $this->client->loginUser($user);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->post(
                uri: "/api/contents/{$otherUserProfile->getId()}",
                content: json_encode([
                    'name' => 'Chat',
                ], JSON_THROW_ON_ERROR),
                headers: ['CONTENT_TYPE' => 'application/json']
            );
        } catch (AccessDeniedException $e) {
            ContentFactory::assert()->count(0);

            throw $e;
        }
    }

    public function testCannotCreateWithoutName(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();

        UserFactory::assert()->count(1);
        ContentFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->post(
            uri: "/api/contents/{$user->getId()}",
            content: json_encode([
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        ContentFactory::assert()->count(0);
    }

    public function testCannotCreateWhenGuest(): void
    {
        // Arrange & pre-assert
        ContentFactory::assert()->count(0);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->post(
                uri: "/api/contents",
                content: json_encode([
                    'name' => 'Chat',
                ], JSON_THROW_ON_ERROR),
                headers: ['CONTENT_TYPE' => 'application/json']
            );
        } catch (AccessDeniedException $e) {
            ContentFactory::assert()->count(0);

            throw $e;
        }
    }
}
