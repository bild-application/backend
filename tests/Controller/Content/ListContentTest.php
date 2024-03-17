<?php

namespace App\Tests\Controller\Content;

use App\Factory\ContentFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class ListContentTest extends AbstractTest
{
    use Factories;

    public function testCanListContentUserOwn(): void
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

    public function testCannotListContentUserDontOwn(): void
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

    public function testCannotListWhenGuest(): void
    {
        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/contents",
        );
    }
}
