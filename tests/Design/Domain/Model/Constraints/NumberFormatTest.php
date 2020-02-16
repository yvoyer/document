<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\FloatValue;
use Star\Component\Document\Design\Domain\Model\Constraints\NumberFormat;

final class NumberFormatTest extends TestCase
{
    public function test_it_should_not_allow_number_without_valid_decimal(): void
    {
        $constraint = new NumberFormat();
        $constraint->validate(
            'name',
            FloatValue::fromString('123.456'),
            $errors = new ErrorList()
        );
        $this->assertTrue($errors->hasErrors());
        $this->assertSame(
            'Property "name" expects a number of format "TTT,CCC.DD", "float(123.456)" given.',
            $errors->getErrorsForProperty('name', 'en')[0]
        );
    }

    public function test_it_should_not_allow_number_without_valid_decimal_point(): void
    {
        $constraint = new NumberFormat();
        $constraint->validate(
            'name',
            FloatValue::fromString('1234'),
            $errors = new ErrorList()
        );
        $this->assertTrue($errors->hasErrors());
        $this->assertSame(
            'Property "name" expects a number of format "TTT,CCC.DD", "float(1234)" given.',
            $errors->getErrorsForProperty('name', 'en')[0]
        );
    }

    public function test_it_should_not_allow_number_without_valid_thousands_separator(): void
    {
        $constraint = new NumberFormat();
        $constraint->validate(
            'name',
            FloatValue::fromString('12000.22'),
            $errors = new ErrorList()
        );
        $this->assertTrue($errors->hasErrors());
        $this->assertSame(
            'Property "name" expects a number of format "TTT,CCC.DD", "float(12000.22)" given.',
            $errors->getErrorsForProperty('name', 'en')[0]
        );
    }

    public function test_it_should_allow_number_with(): void
    {
        $constraint = new NumberFormat();
        $constraint->validate(
            'name',
            FloatValue::fromString('1,234.56'),
            $errors = new ErrorList()
        );

        $this->assertFalse($errors->hasErrors());
    }
}
