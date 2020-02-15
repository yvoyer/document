<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Values\OptionListValue;

final class AllowMultipleOptionsTest extends TestCase
{
    /**
     * @var AllowMultipleOptions
     */
    private $constraint;

    public function setUp(): void
    {
        $this->constraint = new AllowMultipleOptions();
    }

    public function test_it_should_error_when_setting_multiple_values_on_single_value_property(): void
    {
        $this->markTestSkipped('todo');
        $this->constraint->validate(
            $name = 'name',
            OptionListValue::withElements(3),
            $errors = new ErrorList()
        );
        $this->assertCount(1, $errors);
        $this->assertSame(
            'Property named "name" allows only one option, "list([Label 1;Label 2;Label 3])" given.',
            $errors->getErrorsForProperty($name, 'en')[0]
        );
    }

    public function test_it_should_be_build_from_constraint_data(): void
    {
        $source = new AllowMultipleOptions();
        $this->assertEquals($source, AllowMultipleOptions::fromParameterData($source->toParameterData()));
    }
}