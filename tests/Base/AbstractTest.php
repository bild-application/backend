<?php

declare(strict_types=1);

namespace App\Tests\Base;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractTest.
 */
abstract class AbstractTest extends WebTestCase
{
    protected static Application $application;

    protected static EntityManagerInterface $manager;

    protected KernelBrowser $client;

    protected Generator $faker;

    protected string $token = '';

    protected function tearDown(): void
    {
        self::$manager->clear();
        $this->faker = Factory::create('fr_FR');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->initialize();
    }

    protected function initialize(): void
    {
        static::ensureKernelShutdown();

        $this->faker = Factory::create();
        $this->client = static::createClient([
            'environment' => 'test',
            'debug' => true,
        ]);

        // Useful to not display all 29049 lines of html when a request fail
        $this->client->catchExceptions(false);

        $manager = self::getContainer()->get('doctrine.orm.entity_manager');

        if ($manager instanceof EntityManagerInterface) {
            self::$manager = $manager;
        }
    }

    protected function loginAdmin(): void
    {
        $this->post('/api/login', [
            'username' => 'admin@admin.fr',
            'password' => 'admin',
        ]);

        // TODO arthaud : modifier pour récupérer le cookie
        $response = $this->getResponseContent(true);

        $this->token = $response['access_token'] ?? null;
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function get(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        $headers = $this->addAuthorizationHeader($headers);

        return $this->client->request('GET', $uri, $params, $files, $headers, $content);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function post(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        $headers = $this->addAuthorizationHeader($headers);

        return $this->client->request('POST', $uri, $params, $files, $headers, $content);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function delete(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        $headers = $this->addAuthorizationHeader($headers);

        return $this->client->request('DELETE', $uri, $params, $files, $headers, $content);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function patch(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        $headers = $this->addAuthorizationHeader($headers);

        return $this->client->request('PATCH', $uri, $params, $files, $headers, $content);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function put(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        $headers = $this->addAuthorizationHeader($headers);

        return $this->client->request('PUT', $uri, $params, $files, $headers, $content);
    }

    /**
     * @return string|mixed[]
     */
    protected function getResponseContent(bool $json = false): string|array
    {
        if ($json) {
            return json_decode($this->client->getResponse()->getContent(), true);
        }

        return $this->client->getResponse()->getContent();
    }

    protected function getStatusCode(): int
    {
        return $this->client->getResponse()->getStatusCode();
    }

    /**
     * @param string[] $headers
     *
     * @return string[]
     */
    private function addAuthorizationHeader(array $headers): array
    {
        if ($this->token === '') {
            return $headers;
        }

        $authorization = [
            'HTTP_Authorization' => 'Bearer ' . $this->token,
        ];

        return array_merge($authorization, $headers);
    }
}
