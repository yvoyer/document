<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class AllTest extends TestCase
{
    public function test_it_should_validate_all_constraints()
    {
        $constraint = new All(
            $c1 = $this->createMock(PropertyConstraint::class),
            $c2 = $this->createMock(PropertyConstraint::class),
            $c3 = $this->createMock(PropertyConstraint::class)
        );

        $c1->expects($this->once())
            ->method('validate');

        $c2->expects($this->once())
            ->method('validate');

        $c3->expects($this->once())
            ->method('validate');

        $constraint->validate(
            PropertyDefinition::fromString('name', NullType::class),
            'test'
        );
    }
}
