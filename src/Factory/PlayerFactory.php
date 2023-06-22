<?php

namespace App\Factory;

use App\Entity\Player;
use App\Enums\PositionEnum;
use App\Repository\PlayerRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Player>
 *
 * @method        Player|Proxy create(array|callable $attributes = [])
 * @method static Player|Proxy createOne(array $attributes = [])
 * @method static Player|Proxy find(object|array|mixed $criteria)
 * @method static Player|Proxy findOrCreate(array $attributes)
 * @method static Player|Proxy first(string $sortedField = 'id')
 * @method static Player|Proxy last(string $sortedField = 'id')
 * @method static Player|Proxy random(array $attributes = [])
 * @method static Player|Proxy randomOrCreate(array $attributes = [])
 * @method static PlayerRepository|RepositoryProxy repository()
 * @method static Player[]|Proxy[] all()
 * @method static Player[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Player[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Player[]|Proxy[] findBy(array $attributes)
 * @method static Player[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Player[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class PlayerFactory extends ModelFactory
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
        $positions = PositionEnum::getAllValues();

        $positionIndex = array_rand($positions);

        return [
            'position'  => $positions[$positionIndex],
            'lastName'  => self::faker()->lastName(),
            'firstName' => self::faker()->firstName("Male")
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Player $player): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Player::class;
    }
}
