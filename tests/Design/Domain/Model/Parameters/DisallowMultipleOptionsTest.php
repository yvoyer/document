<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\Design\Domain\Model\Parameters\DisallowMultipleOptions;

final class DisallowMultipleOptionsTest extends TestCase
{
    public function test_it_should_be_build_from_constraint_data(): void
    {
        $source = new DisallowMultipleOptions();
        $this->assertEquals($source, DisallowMultipleOptions::fromParameterData($source->toParameterData()));
    }

    public function test_it_should_error_when_list_contains_more_than_one_value(): void
    {
        $parameter = new DisallowMultipleOptions();
        $parameter->validate('name', ArrayOfInteger::withValues(1, 2), $errors = new ErrorList());

        self::assertTrue($errors->hasErrors());
        self::assertCount(1, $errors);
        self::assertSame(
            ['Property named "name" allows only 1 option, "list(1;2)" given.'],
            $errors->getLocalizedMessages('en')
        );
        self::assertSame(
            ['Property named "name" allows only 1 option, "list(1;2)" given.'],
            $errors->getErrorsForProperty('name', 'en')
        );
    }
}
