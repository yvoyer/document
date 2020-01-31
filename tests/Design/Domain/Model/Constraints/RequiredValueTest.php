<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Exception\EmptyRequiredValue;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class RequiredValueTest extends TestCase
{
    /**
     * @var RequiresValue
     */
    private $constraint;

    public function setUp(): void
    {
        $this->constraint = new RequiresValue();
    }

    public function test_it_should_throw_exception_when_value_empty(): void
    {
        $this->expectException(EmptyRequiredValue::class);
        $this->expectExceptionMessage('Property named "name" is required, but empty value given.');
        $this->constraint->validate(
            new PropertyDefinition(PropertyName::fromString('name'), new NullType()),
            ''
        );
    }

    public function test_it_should_throw_exception_when_array_value_empty(): void
    {
        $this->expectException(EmptyRequiredValue::class);
        $this->expectExceptionMessage('Property named "name" is required, but empty value given.');
        $this->constraint->validate(
            new PropertyDefinition(PropertyName::fromString('name'), new NullType()),
            []
        );
    }
}
