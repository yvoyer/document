<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\Constraints\Regex;

final class RegexTest extends TestCase
{
    public function test_it_should_allow_value(): void
    {
        $constraint = new Regex('/\d+/');
        $constraint->validate('name', StringValue::fromString('123'), $errors = new ErrorList());
        $this->assertFalse($errors->hasErrors());
    }

    public function test_it_should_not_allow_value(): void
    {
        $constraint = new Regex('/\d+/');
        $constraint->validate($name = 'name', StringValue::fromString('abc'), $errors = new ErrorList());
        $this->assertTrue($errors->hasErrors());
        $propErrors = $errors->getErrorsForProperty($name, 'en');
        $this->assertCount(1, $propErrors);
        $this->assertSame('Value "string(abc)" do not match pattern "/\d+/".', $propErrors[0]);
    }

    public function test_it_should_throw_exception_when_empty_pattern(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Pattern "" is empty, but non empty value was expected.');
        new Regex('');
    }

    public function test_it_should_throw_exception_when_invalid_pattern(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Pattern "asd" is not a valid regex.');
        new Regex('asd');
    }

    public function test_it_should_be_build_from_constraint_data(): void
    {
        $source = new Regex('/\d+/');
        $this->assertEquals($source, Regex::fromData($source->toData()));
    }
}
