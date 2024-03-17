<?php

namespace App\Tests\Controller\Package;

use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

class CreatePackageTest extends AbstractTest
{
    use Factories;

    public function testCanCreate(): void
    {
        // Arrange & pre-assert
        $userProxy = UserFactory::createOne();
        $user = $userProxy->object();
        $profileProxy = ProfileFactory::createOne(['user' => $userProxy]);
        $profile = $userProxy->object();

        UserFactory::assert()->count(1);
        ProfileFactory::assert()->count(0);

        $this->client->loginUser($user);

        // Act
        $this->jsonPost(
            uri: '/api/packages',
            content: [
                'name' => 'package_name',
                'profile' => $profileProxy,
            ],
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        ProfileFactory::assert()->count(1);
    }
}
