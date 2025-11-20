<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentTypeName;
use PHPUnit\Framework\TestCase;

final class DocumentTypeNameTest extends TestCase
{
    public function test_it_should_allow_empty_name(): void
    {
        self::assertSame('', DocumentTypeName::fromLocalizedString('', 'locale')->toString());
        self::assertTrue(DocumentTypeName::fromLocalizedString('', 'locale')->isEmpty());
        self::assertFalse(DocumentTypeName::fromLocalizedString('not-empty', 'locale')->isEmpty());
    }

    public function test_it_should_trim_name(): void
    {
        self::assertTrue(DocumentTypeName::fromLocalizedString('   ', 'locale')->isEmpty());
        self::assertSame('', DocumentTypeName::fromLocalizedString('   ', 'locale')->toString());
    }

    public function test_it_should_be_serializable(): void
    {
        $name = DocumentTypeName::fromLocalizedString('name', 'en');
        self::assertSame(
            'a:2:{s:7:"content";s:4:"name";s:6:"locale";s:2:"en";}',
            $name->toSerializableString()
        );
    }

    public function test_it_should_be_built_from_serializable_string(): void
    {
        $name = DocumentTypeName::fromSerializedString(
            'a:2:{s:7:"content";s:7:"my-name";s:6:"locale";s:2:"en";}'
        );
        self::assertSame('my-name', $name->toString());
        self::assertSame('en', $name->locale());
    }

    public function test_it_should_allow_integer_as_name(): void
    {
        $name = DocumentTypeName::fromSerializedString(
            'a:2:{s:7:"content";i:42;s:6:"locale";s:2:"en";}'
        );
        self::assertSame('42', $name->toString());
    }
}
