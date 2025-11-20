<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\InvalidPropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use PHPUnit\Framework\TestCase;

final class PropertyNameTest extends TestCase
{
    public function test_it_should_create_a_name(): void
    {
        $name = PropertyName::fromLocalizedString('name', 'en');
        self::assertSame('name', $name->toString());
        self::assertSame('en', $name->locale());
    }

    public function test_it_should_not_allow_empty_name(): void
    {
        $this->expectException(InvalidPropertyName::class);
        $this->expectDeprecationMessage('Property name "" may not be empty.');
        PropertyName::fromLocalizedString('', 'en');
    }

    public function test_it_should_be_created_from_serialized_string(): void
    {
        $name = PropertyName::fromSerializedString(
            'a:2:{s:7:"content";s:4:"name";s:6:"locale";s:2:"en";}'
        );
        self::assertSame('name', $name->toString());
        self::assertSame('en', $name->locale());
    }
}
