<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Exception\TooManyValues;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class RequireSingleOptionTest extends TestCase
{
    /**
     * @var RequiresSingleOption
     */
    private $constraint;

    public function setUp(): void
    {
        $this->constraint = new RequiresSingleOption();
    }

    public function test_it_should_throw_exception_when_setting_multiple_values_on_single_value_property(): void
    {
        $this->expectException(TooManyValues::class);
        $this->expectExceptionMessage('Property named "name" requires maximum one option, "[1,2]" given.');
        $this->constraint->validate(
            new PropertyDefinition(PropertyName::fromString('name'), new NullType()),
            [1, 2]
        );
    }
}
