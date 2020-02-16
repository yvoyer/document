<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Infrastructure\Persistence\InMemory;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\ConstraintFactory;
use function sprintf;

final class ConstraintFactoryTest extends TestCase
{
    public function test_it_should_throw_exception_when_class_is_not_valid_sub_class(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Class "invalid" was expected to be subclass of "' . Constraint::class
        );
        new ConstraintFactory(['alias' => 'invalid']);
    }

    public function test_it_should_register_a_map(): void
    {
        $registry = new ConstraintFactory(['alias' => NoConstraint::class]);
        $registry->addClassMap('type', NoConstraint::class);

        self::assertInstanceOf(NoConstraint::class, $registry->createPropertyConstraint('alias', []));
        self::assertInstanceOf(NoConstraint::class, $registry->createPropertyConstraint('type', []));
    }

    public function test_it_should_throw_exception_when_map_already_exists(): void
    {
        $factory = new ConstraintFactory(
            [
                'alias' => PropertyConstraint::class,
            ]
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Cannot map the classmap "%s". The alias "alias" is already registered with class map "%s".',
                DocumentConstraint::class,
                PropertyConstraint::class
            )
        );
        $factory->addClassMap('alias', DocumentConstraint::class);
    }

    public function test_it_should_throw_exception_when_alias_not_found(): void
    {
        $factory = new ConstraintFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Constraint class map "invalid" does not exists, did you register it?');
        $factory->createPropertyConstraint('invalid', []);
    }
}
