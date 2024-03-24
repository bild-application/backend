<?php

namespace App\Tests\Feature\Content;

use App\Factory\ContentFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class DeleteContentTest extends AbstractTest
{
    use Factories;

    public function test_can_delete_content_user_own(): void
    {
        // Arrange & pre-assert
        $content = ContentFactory::createOne(['user' => UserFactory::createOne()]);
        $imagePath = $content->getImage();
        $user = $content->getUser();

        ContentFactory::assert()->count(1);
        UserFactory::assert()->count(1);

        $this->client->loginUser($user);

        // Act
        $this->delete(
            uri: "/api/contents/{$content->getId()}",
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        ContentFactory::assert()->count(0);
        UserFactory::assert()->count(1);
        self::assertFsFileDoesNotExists($imagePath);
    }

    public function test_cannot_delete_without_id(): void
    {
        // Arrange & pre-assert
        $content = ContentFactory::createOne(['user' => UserFactory::createOne()]);
        $imagePath = $content->getImage();
        $user = $content->getUser();

        ContentFactory::assert()->count(1);
        self::assertFsFileExists(ContentFactory::first()->getImage());
        UserFactory::assert()->count(1);

        $this->client->loginUser($user);

        // Act
        $this->expectException(NotFoundHttpException::class);

        try {
            $this->delete(
                uri: "/api/contents/",
                headers: ['CONTENT_TYPE' => 'application/json']
            );
        } catch (NotFoundHttpException $e) {
            // Assert
            ContentFactory::assert()->count(1);
            self::assertFsFileExists($imagePath);
            UserFactory::assert()->count(1);

            throw $e;
        }
    }

    public function test_cannot_delete_non_existent_id(): void
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
        try {
            $this->jsonDelete(
                uri: "/api/contents/{$contentId}",
            );
        } catch (NotFoundHttpException $e) {
            // Assert
            ContentFactory::assert()->count(0);
            UserFactory::assert()->count(1);

            throw $e;
        }
    }

    public function test_cannot_delete_content_user_dont_own(): void
    {
        // Arrange & pre-assert
        $content = ContentFactory::createOne(['user' => UserFactory::createOne()]);
        $imagePath = $content->getImage();
        $notTheOwner = UserFactory::createOne()->object();

        ContentFactory::assert()->count(1);
        UserFactory::assert()->count(2);

        $this->client->loginUser($notTheOwner);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonDelete(
                uri: "/api/contents/{$content->getId()}",
            );
        } catch (AccessDeniedException $e) {
            // Assert
            ContentFactory::assert()->count(1);
            self::assertFsFileExists($imagePath);

            throw $e;
        }
    }

    public function test_cannot_delete_when_guest(): void
    {
        // Arrange & pre-assert
        $content = ContentFactory::createOne(['user' => UserFactory::createOne()]);
        $imagePath = $content->getImage();

        UserFactory::assert()->count(1);
        ContentFactory::assert()->count(1);

        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $this->jsonDelete(
                uri: "/api/contents/{$content->getId()}",
            );
        } catch (AccessDeniedException $e) {
            // Assert
            ContentFactory::assert()->count(1);
            self::assertFsFileExists($imagePath);

            throw $e;
        }
    }
}
