<?php

namespace App\Tests\Controller\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

class ListProfileTest extends AbstractTest
{
    use Factories;

    public function testCanListProfileUserOwn(): void
    {
        $this->markTestIncomplete('TODO : assert response not empty array');

        // Arrange & pre-assert
        $userProxy = UserFactory::createOne();
        ProfileFactory::createMany(2, ['user' => $userProxy]);
        $user = $userProxy->object();

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(2);

        $this->client->loginUser($user);

        // Act
        $this->get(
            uri: '/api/profiles',
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseIsSuccessful();
    }

    public function testCannotListProfilesUserDontOwn(): void
    {
        $this->markTestIncomplete('TODO : assert response empty array');

        // Arrange & pre-assert
        $user = UserFactory::createOne()->object();
        ProfileFactory::createMany(2, ['user' => UserFactory::createOne()]);

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(2);

        $this->client->loginUser($user);

        // Act
        $this->get(
            uri: '/api/profiles',
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseIsSuccessful();
    }

    public function testCannotListWhenGuest(): void
    {
        // Arrange & pre-assert
        ProfileFactory::createMany(2, ['user' => UserFactory::createOne()]);

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(2);

        // Act
        $this->get(
            uri: '/api/profiles',
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
