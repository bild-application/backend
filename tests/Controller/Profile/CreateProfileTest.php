<?php

namespace App\Tests\Controller\Profile;

use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use function json_encode;
use const JSON_THROW_ON_ERROR;

class CreateProfileTest extends AbstractTest
{
    use Factories;

    public function testCanCreate(): void
    {
        $this->markTestIncomplete('miss profile count assertion in bdd');

        $user = UserFactory::new()->create()->object();

        $this->client->loginUser($user);

        $this->post(
            uri: '/api/profiles',
            content: json_encode([
                'name' => 'Paul',
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseIsSuccessful();
        // TODO @arthaud assert there is one Profile in BDD
    }

    public function testCannotCreateWithoutName(): void
    {
        $this->markTestIncomplete('miss profile count assertion in bdd');

        $user = UserFactory::new()->create()->object();

        $this->client->loginUser($user);

        $this->post(
            uri: '/api/profiles',
            content: json_encode([
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        // TODO @arthaud assert there is no Profile in BDD
    }
}
