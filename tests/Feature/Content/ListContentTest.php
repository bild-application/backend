<?php

namespace App\Tests\Feature\Content;

use App\Factory\ContentFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class ListContentTest extends AbstractTest
{
    use Factories;

    public function test_can_list_contents_of_profile_user_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        ContentFactory::createMany(2, ['user' => $user]);

        $profile = ProfileFactory::createOne(['user' => $user]);
        ContentFactory::createMany(3, ['user' => $user, 'profile' => $profile]);

        $otherProfile = ProfileFactory::createOne(['user' => $user]);
        ContentFactory::createMany(4, ['user' => $user, 'profile' => $otherProfile]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profile->getId()}/contents",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertCount(5, $json);
    }

    public function test_cannot_list_contents_of_profile_user_dont_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        ContentFactory::createMany(3, ['user' => $user, 'profile' => $profile]);

        $notTheOwner = UserFactory::createOne();

        $this->client->loginUser($notTheOwner->object());

        // Assert
        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profile->getId()}/contents",
        );
    }

    public function test_cannot_list_contents_when_guest(): void
    {
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profile->getId()}/contents",
        );
    }
}
