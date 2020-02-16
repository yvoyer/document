<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;
use Star\Component\Document\Design\Domain\Model\Constraints\RequiresOptionCount;

final class RequiresOptionCountTest extends TestCase
{
    public function test_it_should_error_when_setting_multiple_values_on_single_value_property(): void
    {
        $constraint = new RequiresOptionCount(2);
        $constraint->validate(
            $name = 'name',
            OptionListValue::withElements(1),
            $errors = new ErrorList()
        );

        $this->assertCount(1, $errors);
        $this->assertSame(
            'Property named "name" requires at least 2 option(s), "options(Label 1)" given.',
            $errors->getErrorsForProperty($name, 'en')[0]
        );
    }

    public function test_it_should_not_error_when_setting_same_number_as_minimum_count(): void
    {
        $constraint = new RequiresOptionCount(2);
        $constraint->validate(
            $name = 'name',
            ArrayOfInteger::withElements(2),
            $errors = new ErrorList()
        );

        $this->assertCount(0, $errors);
    }

    public function test_it_should_not_error_when_setting_three_value_but_requires_minimum_of_two(): void
    {
        $constraint = new RequiresOptionCount(2);
        $constraint->validate(
            $name = 'name',
            ArrayOfInteger::withElements(3),
            $errors = new ErrorList()
        );

        $this->assertCount(0, $errors);
    }
}
