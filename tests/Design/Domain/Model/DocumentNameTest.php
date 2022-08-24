<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentName;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\InvalidDocumentTypeName;

final class DocumentNameTest extends TestCase
{
    public function test_it_should_not_allow_empty_name(): void
    {
        $this->expectException(InvalidDocumentTypeName::class);
        $this->expectDeprecationMessage('Document type name "" cannot be empty.');
        DocumentName::fromLocalizedString('', 'locale');
    }

    public function test_it_should_be_serializable(): void
    {
        $name = DocumentName::fromLocalizedString('name', 'en');
        self::assertSame(
            'a:2:{s:7:"content";s:4:"name";s:6:"locale";s:2:"en";}',
            $name->toSerializableString()
        );
    }

    public function test_it_should_be_built_from_serializable_string(): void
    {
        $name = DocumentName::fromSerializedString(
            'a:2:{s:7:"content";s:7:"my-name";s:6:"locale";s:2:"en";}'
        );
        self::assertSame('my-name', $name->toString());
        self::assertSame('en', $name->locale());
    }

    public function test_it_should_allow_integer_as_name(): void
    {
        $name = DocumentName::fromSerializedString(
            'a:2:{s:7:"content";i:42;s:6:"locale";s:2:"en";}'
        );
        self::assertSame('42', $name->toString());
    }
}
