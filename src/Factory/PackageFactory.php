<?php

namespace App\Factory;

use App\Entity\Package;
use App\Repository\PackageRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Package>
 *
 * @method        Package|Proxy                     create(array|callable $attributes = [])
 * @method static Package|Proxy                     createOne(array $attributes = [])
 * @method static Package|Proxy                     find(object|array|mixed $criteria)
 * @method static Package|Proxy                     findOrCreate(array $attributes)
 * @method static Package|Proxy                     first(string $sortedField = 'id')
 * @method static Package|Proxy                     last(string $sortedField = 'id')
 * @method static Package|Proxy                     random(array $attributes = [])
 * @method static Package|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PackageRepository|RepositoryProxy repository()
 * @method static Package[]|Proxy[]                 all()
 * @method static Package[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Package[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Package[]|Proxy[]                 findBy(array $attributes)
 * @method static Package[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Package[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Package> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Package> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Package> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Package> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Package> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Package> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Package> random(array $attributes = [])
 * @phpstan-method static Proxy<Package> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Package> repository()
 * @phpstan-method static list<Proxy<Package>> all()
 * @phpstan-method static list<Proxy<Package>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Package>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Package>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Package>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Package>> randomSet(int $number, array $attributes = [])
 */
final class PackageFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->word(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(Package $package): void {})
            ;
    }

    protected static function getClass(): string
    {
        return Package::class;
    }
}
