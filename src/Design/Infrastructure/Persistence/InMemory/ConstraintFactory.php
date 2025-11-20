<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\InMemory;

use Assert\Assertion;
use InvalidArgumentException;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintRegistry;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use function array_key_exists;
use function array_keys;
use function array_map;
use function sprintf;

/**
 * todo check if still useful
 */
final class ConstraintFactory implements ConstraintRegistry
{
    /**
     * @var string[]
     */
    private array $classMap = [];

    /**
     * @param string[] $classMap
     */
    public function __construct(array $classMap = [])
    {
        array_map(
            function (string $class, string $alias) {
                $this->addClassMap($alias, $class);
            },
            $classMap,
            array_keys($classMap)
        );
    }

    /**
     * @param string $alias
     * @param class-string $className
     */
    public function addClassMap(string $alias, string $className): void
    {
        Assertion::subclassOf($className, Constraint::class);
        if (array_key_exists($alias, $this->classMap)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot map the classmap "%s". The alias "%s" is already registered with class map "%s".',
                    $className,
                    $alias,
                    $this->classMap[$alias]
                )
            );
        }

        $this->classMap[$alias] = $className;
    }

    /**
     * @param string $alias
     * @param mixed[] $arguments
     * @return PropertyConstraint
     */
    public function createPropertyConstraint(string $alias, array $arguments): PropertyConstraint
    {
        Assertion::keyExists(
            $this->classMap,
            $alias,
            sprintf('Constraint class map "%s" does not exists, did you register it?', $alias)
        );

        return ConstraintData::fromClass($this->classMap[$alias], $arguments)->createPropertyConstraint();
    }
}
