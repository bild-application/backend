<?php

namespace App\Tests\Feature\Package;

use App\Factory\PackageFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

class UpdatePackageTest extends AbstractTest
{
    use Factories;

    public function test_can_update_with_a_profile_user_own(): void
    {
        // Arrange & pre-assert
        $user = UserFactory::createOne();
        $profile = ProfileFactory::createOne(['user' => $user]);
        $package = PackageFactory::createOne(['profile' => $profile]);

        $this->client->loginUser($user->object());

        // Act
        $this->jsonPost(
            uri: "/api/packages/{$package->getid()}",
            content: ['name' => 'new name']
        );

        // Assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        PackageFactory::assert()->count(1);
        
        $package->refresh();
        self::assertEquals('new name', $package->getName());
    }
}
