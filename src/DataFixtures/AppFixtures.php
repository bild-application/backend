<?php

namespace App\DataFixtures;

use App\Factory\ContentFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $aliceAndBobParents = UserFactory::createOne([
            'email' => 'user@email.com',
            'password' => 'password',
        ]);

        ContentFactory::createMany(10, [
            'user' => $aliceAndBobParents,
        ]);

        $aliceProfile = ProfileFactory::createOne([
            'name' => 'Alice',
            'user' => $aliceAndBobParents,
        ]);

        ContentFactory::createMany(3, [
            'user' => $aliceAndBobParents,
            'profile' => $aliceProfile,
        ]);

        $bobProfile = ProfileFactory::createOne([
            'name' => 'Bob',
            'user' => $aliceAndBobParents,
        ]);

        ContentFactory::createMany(2, [
            'user' => $aliceAndBobParents,
            'profile' => $bobProfile,
        ]);
    }
}
