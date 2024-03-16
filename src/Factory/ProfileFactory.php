<?php

namespace App\Factory;

use App\Entity\Profile;
use App\Repository\ProfileRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Profile>
 *
 * @method        Profile|Proxy                     create(array|callable $attributes = [])
 * @method static Profile|Proxy                     createOne(array $attributes = [])
 * @method static Profile|Proxy                     find(object|array|mixed $criteria)
 * @method static Profile|Proxy                     findOrCreate(array $attributes)
 * @method static Profile|Proxy                     first(string $sortedField = 'id')
 * @method static Profile|Proxy                     last(string $sortedField = 'id')
 * @method static Profile|Proxy                     random(array $attributes = [])
 * @method static Profile|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProfileRepository|RepositoryProxy repository()
 * @method static Profile[]|Proxy[]                 all()
 * @method static Profile[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Profile[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Profile[]|Proxy[]                 findBy(array $attributes)
 * @method static Profile[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Profile[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Profile> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Profile> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Profile> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Profile> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Profile> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Profile> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Profile> random(array $attributes = [])
 * @phpstan-method static Proxy<Profile> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Profile> repository()
 * @phpstan-method static list<Proxy<Profile>> all()
 * @phpstan-method static list<Proxy<Profile>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Profile>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Profile>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Profile>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Profile>> randomSet(int $number, array $attributes = [])
 */
final class ProfileFactory extends ModelFactory
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
            'name' => self::faker()->sentence(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(Profile $profile): void {})
            ;
    }

    protected static function getClass(): string
    {
        return Profile::class;
    }
}
