<?php

namespace App\Tests\Trait;

use Symfony\Component\DomCrawler\Crawler;
use function array_merge;
use function json_decode;
use function json_encode;
use const JSON_THROW_ON_ERROR;

trait RequestUtils
{
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
