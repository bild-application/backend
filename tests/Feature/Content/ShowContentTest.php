<?php

namespace App\Tests\Feature\Content;

use App\Factory\ContentFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class ShowContentTest extends AbstractTest
{
    use Factories;

    public function test_can_see_content_user_own(): void
    {
        // Arrange & pre-assert
        $content = ContentFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $content->getUser();

        UserFactory::assert()->count(1);
        ContentFactory::assert()->count(1);

        $this->client->loginUser($user);

        // Act
        $this->jsonGet(
            uri: "/api/contents/{$content->getId()}",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function test_cannot_see_non_existent_id(): void
    {
        // Arrange & pre-assert
        $content = ContentFactory::createOne(['user' => UserFactory::createOne()]);
        $user = $content->getUser();
        $contentId = $content->getId();
        $content->remove();

        ContentFactory::assert()->count(0);
        UserFactory::assert()->count(1);

        $this->client->loginUser($user);

        $this->expectException(NotFoundHttpException::class);

        // Act
        $this->jsonGet(
            uri: "/api/contents/{$contentId}",
        );
    }

    public function test_cannot_see_content_user_dont_own(): void
    {
        // Arrange & pre-assert
        $content = ContentFactory::createOne(['user' => UserFactory::createOne()]);
        $notTheOwner = UserFactory::createOne()->object();

        UserFactory::assert()->count(2);
        ContentFactory::assert()->count(1);

        $this->client->loginUser($notTheOwner);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/contents/{$content->getId()}",
        );
    }

    public function test_cannot_see_content_when_guest(): void
    {
        // Arrange & pre-assert
        $content = ContentFactory::createOne(['user' => UserFactory::createOne()]);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/contents/{$content->getId()}",
        );
    }
}
