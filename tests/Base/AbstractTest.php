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
use function array_merge;
use function json_decode;
use function json_encode;
use const JSON_THROW_ON_ERROR;

/**
 * Class AbstractTest.
 */
abstract class AbstractTest extends WebTestCase
{
    protected static Application $application;

    protected static EntityManagerInterface $manager;

    protected KernelBrowser $client;

    protected Generator $faker;

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

    private function jsonRequest(
        string $method,
        string $uri,
        array $params = [],
        array $content = [],
        array $files = [],
        array $headers = []): Crawler
    {
        return $this->client->request(
            method: $method,
            uri: $uri,
            parameters: $params,
            files: $files,
            server: array_merge(
                ['CONTENT_TYPE' => 'application/json'],
                $headers
            ),
            content: json_encode($content, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function get(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        return $this->client->request('GET', $uri, $params, $files, $headers, $content);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $content
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function jsonGet(string $uri, array $params = [], array $content = [], array $files = [], array $headers = []): Crawler
    {
        return $this->jsonRequest('GET', $uri, $params, $content, $files, $headers);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function post(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        return $this->client->request('POST', $uri, $params, $files, $headers, $content);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $content
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function jsonPost(string $uri, array $params = [], array $content = [], array $files = [], array $headers = []): Crawler
    {
        return $this->jsonRequest('POST', $uri, $params, $content, $files, $headers);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function delete(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        return $this->client->request('DELETE', $uri, $params, $files, $headers, $content);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $content
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function jsonDelete(string $uri, array $params = [], array $content = [], array $files = [], array $headers = []): Crawler
    {
        return $this->jsonRequest('DELETE', $uri, $params, $content, $files, $headers);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function patch(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        return $this->client->request('PATCH', $uri, $params, $files, $headers, $content);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $content
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function jsonPatch(string $uri, array $params = [], array $content = [], array $files = [], array $headers = []): Crawler
    {
        return $this->jsonRequest('PATCH', $uri, $params, $content, $files, $headers);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function put(string $uri, array $params = [], ?string $content = null, array $files = [], array $headers = []): Crawler
    {
        return $this->client->request('PUT', $uri, $params, $files, $headers, $content);
    }

    /**
     * @param mixed[] $params
     * @param mixed[] $content
     * @param mixed[] $files
     * @param string[] $headers
     */
    protected function jsonPut(string $uri, array $params = [], array $content = [], array $files = [], array $headers = []): Crawler
    {
        return $this->jsonRequest('PUT', $uri, $params, $content, $files, $headers);
    }

    /**
     * @return string|mixed[]
     */
    protected function responseContent(): string|array
    {
        return $this->client->getResponse()->getContent();
    }

    protected function jsonResponseContent(): string|array
    {
        return json_decode($this->responseContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    protected function getStatusCode(): int
    {
        return $this->client->getResponse()->getStatusCode();
    }
}
