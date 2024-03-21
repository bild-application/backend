<?php

namespace App\Tests\Feature\Content;

use App\Factory\ContentFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class ListContentTest extends AbstractTest
{
    use Factories;

    public function test_can_list_content_user_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();
        ContentFactory::createMany(4, ['user' => $user]);

        UserFactory::assert()->count(1);
        ContentFactory::assert()->count(4);

        $this->client->loginUser($user);

        // Act
        $this->jsonGet(
            uri: "/api/contents",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertCount(4, $json);
    }

    public function test_cannot_list_content_user_dont_own(): void
    {
        // Arrange & pre-assert
        ContentFactory::createMany(4, ['user' => UserFactory::createOne()]);
        $notTheOwner = UserFactory::createOne()->object();

        ContentFactory::assert()->count(4);
        UserFactory::assert()->count(2);

        $this->client->loginUser($notTheOwner);

        // Act
        $this->jsonGet(
            uri: "/api/contents",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertCount(0, $json);
    }

    public function test_cannot_list_when_guest(): void
    {
        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/contents",
        );
    }
}
