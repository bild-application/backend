<?php

namespace App\Tests\Controller\Profile;

use App\Factory\UserFactory;
use App\Tests\Base\AbstractTest;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use function json_encode;
use const JSON_THROW_ON_ERROR;

class DeleteProfileTest extends AbstractTest
{
    use Factories;

    public function testCanDelete(): void
    {
        $this->markTestIncomplete('miss profile generation and count assertion in bdd');

        $user = UserFactory::new()->create()->object();
        // TODO @arthaud create profile for user

        $this->client->loginUser($user);

        $this->delete(
            uri: '/api/profiles',
            content: json_encode([
                'id' => 'Paul',
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseIsSuccessful();
        // TODO @arthaud assert there is one Profile in BDD
    }

    public function testCannotDeleteWithoutId(): void
    {
        $this->markTestIncomplete('miss profile generation and count assertion in bdd');

        $user = UserFactory::new()->create()->object();
        // TODO @arthaud create profile for user

        $this->client->loginUser($user);

        $this->delete(
            uri: '/api/profiles',
            content: json_encode([
            ], JSON_THROW_ON_ERROR),
            headers: ['CONTENT_TYPE' => 'application/json']
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        // TODO @arthaud assert there is 1 Profile in BDD
    }
}
