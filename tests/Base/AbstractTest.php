<?php

declare(strict_types=1);

namespace App\Tests\Base;

use App\Facade\FileSystemFacade;
use App\Tests\Trait\FileSystemAssertions;
use App\Tests\Trait\RequestUtils;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;

/**
 * Class AbstractTest.
 */
abstract class AbstractTest extends WebTestCase
{
    use RequestUtils;
    use FileSystemAssertions;

    protected static Application $application;

    protected static EntityManagerInterface $manager;

    protected KernelBrowser $client;

    protected Generator $faker;

    protected static FileSystemFacade $fileSystem;

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

        $fileSystem = self::getContainer()->get(FileSystemFacade::class);

        if ($fileSystem instanceof FileSystemFacade) {
            static::$fileSystem = $fileSystem;
        }
    }
}
