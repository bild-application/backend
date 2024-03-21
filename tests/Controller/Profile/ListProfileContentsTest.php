<?php

namespace App\Tests\Controller\Profile;

use App\Factory\ContentFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class ListProfileContentsTest extends AbstractTest
{
    use Factories;

    public function testCanListContentsOfProfileUserOwn(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();
        $profile = ProfileFactory::createOne(['user' => $user]);
        ContentFactory::createMany(2, ['profile' => $profile]);

        ContentFactory::assert()->count(2);

        $this->client->loginUser($user);

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profile->getId()}/contents",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertCount(2, $json);
    }

    public function testOnlyReturnContentsOfProfileSpecified(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();
        $profileA = ProfileFactory::createOne(['user' => $user]);
        $profileB = ProfileFactory::createOne(['user' => $user]);
        ContentFactory::createMany(2, ['profile' => $profileA]);
        ContentFactory::createMany(2, ['profile' => $profileB]);

        ContentFactory::assert()->count(4);

        $this->client->loginUser($user);

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profileA->getId()}/contents",
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertCount(2, $json);
    }

    public function testCannotListContentsOfProfileUserDontOwn(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);
        $notTheOwner = UserFactory::createOne()->object();
        ContentFactory::createMany(2, ['profile' => $profile]);

        ProfileFactory::assert()->count(2);

        $this->client->loginUser($notTheOwner);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profile->getId()}/contents",
        );
    }

    public function testCannotListContentsOfProfileWhenGuest(): void
    {
        // Arrange & pre-assert
        $profile = ProfileFactory::createOne(['user' => UserFactory::createOne()]);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: "/api/profiles/{$profile->getId()}/contents",
        );
    }
}
