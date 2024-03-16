<?php

namespace App\Tests\Controller\Profile;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\Factories;
use function json_encode;

class ListProfileTest extends AbstractTest
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
        $this->get(
            uri: '/api/profiles',
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        self::assertResponseIsSuccessful();
        $json = $this->getResponseContent(true);
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
        $this->get(
            uri: '/api/profiles',
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        // Assert
        $json = $this->getResponseContent(true);
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
        $this->get(
            uri: '/api/profiles',
            headers: ['CONTENT_TYPE' => 'application/json']
        );
    }
}
