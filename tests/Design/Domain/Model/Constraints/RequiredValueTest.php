<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\ListValue;

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

    public function test_it_should_error_when_value_empty(): void
    {
        $this->constraint->validate($name = 'name', new EmptyValue(), $errors = new ErrorList());
        $this->assertTrue($errors->hasErrors());
        $this->assertSame(
            'Property named "name" is required, but empty value given.',
            $errors->getErrorsForProperty($name, 'en')[0]
        );
    }

    public function test_it_should_error_when_array_value_empty(): void
    {
        $this->constraint->validate($name = 'name', new ListValue(), $errors = new ErrorList());
        $this->assertTrue($errors->hasErrors());
        $this->assertSame(
            'Property named "name" is required, but empty value given.',
            $errors->getErrorsForProperty($name, 'en')[0]
        );
    }
}
