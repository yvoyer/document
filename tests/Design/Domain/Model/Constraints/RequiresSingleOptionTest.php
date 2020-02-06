<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Values\ListValue;

final class RequiresSingleOptionTest extends TestCase
{
    /**
     * @var RequiresSingleOption
     */
    private $constraint;

    public function setUp(): void
    {
        $this->constraint = new RequiresSingleOption();
    }

    public function test_it_should_error_when_setting_multiple_values_on_single_value_property(): void
    {
        $this->constraint->validate(
            $name = 'name',
            ListValue::withElements(3),
            $errors = new ErrorList()
        );
        $this->assertCount(1, $errors);
        $this->assertSame(
            'Property named "name" allows only one option, "Label 1;Label 2;Label 3" given.',
            $errors->getErrorsForProperty($name, 'en')[0]
        );
    }
}
