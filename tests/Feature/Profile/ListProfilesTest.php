<?php

namespace App\Tests\Feature\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;

class ListProfilesTest extends AbstractTest
{
    use Factories;

    public function testCanListProfileUserOwn(): void
    {
        // Arrange & pre-assert
        $userProxy = UserFactory::createOne();
        ProfileFactory::createMany(2, ['user' => $userProxy]);
        $user = $userProxy->object();

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(2);

        $this->client->loginUser($user);

        // Act
        $this->jsonGet(
            uri: '/api/profiles',
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $this->jsonResponseContent();
        self::assertNotEmpty($json);
    }

    public function testCannotListProfilesUserDontOwn(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();
        ProfileFactory::createMany(2, ['user' => UserFactory::createOne()]);

        UserFactory::assert()->count(2);
        ProfileFactory::assert()->count(2);

        $this->client->loginUser($user);

        // Act
        $this->jsonGet(
            uri: '/api/profiles',
        );

        // Assert
        $json = $this->jsonResponseContent();
        self::assertEmpty($json);
    }

    public function testCannotListWhenGuest(): void
    {
        // Arrange & pre-assert
        ProfileFactory::createMany(2, ['user' => UserFactory::createOne()]);

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(2);

        $this->expectException(AccessDeniedException::class);

        // Act
        $this->jsonGet(
            uri: '/api/profiles',
        );
    }
}
