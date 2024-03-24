<?php

namespace App\Factory;

use App\Entity\Content;
use App\Facade\FileSystemFacade;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Content>
 *
 * @method        Content|Proxy                     create(array|callable $attributes = [])
 * @method static Content|Proxy                     createOne(array $attributes = [])
 * @method static Content|Proxy                     find(object|array|mixed $criteria)
 * @method static Content|Proxy                     findOrCreate(array $attributes)
 * @method static Content|Proxy                     first(string $sortedField = 'id')
 * @method static Content|Proxy                     last(string $sortedField = 'id')
 * @method static Content|Proxy                     random(array $attributes = [])
 * @method static Content|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ContentRepository|RepositoryProxy repository()
 * @method static Content[]|Proxy[]                 all()
 * @method static Content[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Content[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Content[]|Proxy[]                 findBy(array $attributes)
 * @method static Content[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Content[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Content> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Content> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Content> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Content> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Content> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Content> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Content> random(array $attributes = [])
 * @phpstan-method static Proxy<Content> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Content> repository()
 * @phpstan-method static list<Proxy<Content>> all()
 * @phpstan-method static list<Proxy<Content>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Content>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Content>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Content>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Content>> randomSet(int $number, array $attributes = [])
 */
final class ContentFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(
        protected FileSystemFacade $fileSystemFacade
    ) {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $file = new UploadedFile(
            __DIR__ . '/../../tests/Stub/placeholder.jpg',
            'placeholder.jpg'
        );

        $filePath = $this->fileSystemFacade->store($file, Content::STORAGE_FOLDER);

        return [
            'name' => self::faker()->sentence(),
            'image' => $filePath,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(Content $content): void {})
            ;
    }

    protected static function getClass(): string
    {
        return Content::class;
    }
}
