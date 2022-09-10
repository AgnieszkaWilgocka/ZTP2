<?php
/**
 * Base fixtures
 */
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;
use LogicException;

/**
 * Class AbstractBaseFixtures
 */
abstract class AbstractBaseFixtures extends Fixture
{
    /**
     * Faker
     */
    protected Generator $faker;

    /**
     * Persistence object manager
     *
     * @var ObjectManager $manager
     */
    protected ObjectManager $manager;

    /**
     * Load
     *
     * @param ObjectManager $manager
     *
     */

    /**
     * Object reference index.
     *
     * @var array
     */
    private array $referencesIndex = [];

    /**
     * Load
     *
     * @param ObjectManager $manager
     *
     */
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData();
    }

    /**
     * Load data
     */
    abstract protected function loadData(): void;

    /**
     * Create many objects at once
     *
     * @param int      $count
     * @param string   $groupName
     * @param callable $factory
     *
     */
    protected function createMany(int $count, string $groupName, callable $factory): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $entity = $factory($i);

            if (null === $entity) {
                throw new LogicException('Did you forget to return the entity object from your callback to BaseFixture::createMany()?');
            }

            $this->manager->persist($entity);

            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);
        }
    }

    /**
     * Set random reference to the object
     *
     * @param string $groupName
     *
     * @return object
     */
    protected function getRandomReference(string $groupName): object
    {
        if (!isset($this->referencesIndex[$groupName])) {
            $this->referencesIndex[$groupName] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $reference) {
                if (str_starts_with((string) $key, $groupName.'_')) {
                    $this->referencesIndex[$groupName][] = $key;
                }
            }
        }

        if (empty($this->referencesIndex[$groupName])) {
            throw new InvalidArgumentException(sprintf('Did not find any references saved with the group name "%s"', $groupName));
        }

        $randomReferenceKey = (string) $this->faker->randomElement($this->referencesIndex[$groupName]);

        return $this->getReference($randomReferenceKey);
    }

    /**
     * Get array of objects references based on count
     *
     * @param string $groupName
     * @param int    $count
     *
     * @return object[]
     *
     * @psalm-return list<object>
     */
    protected function getRandomReferences(string $groupName, int $count): array
    {
        $references = [];
        while (count($references) < $count) {
            $references[] = $this->getRandomReference($groupName);
        }

        return $references;
    }
}
