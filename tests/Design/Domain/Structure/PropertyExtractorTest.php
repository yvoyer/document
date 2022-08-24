<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Structure;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use PHPUnit\Framework\TestCase;

final class PropertyExtractorTest extends TestCase
{
    private PropertyExtractor $extractor;

    protected function setUp(): void
    {
        $this->extractor = new PropertyExtractor();
    }

    public function test_it_should_throw_exception_when_property_not_found(): void
    {
        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with code "name" could not be found.');
        $this->extractor->getProperty(PropertyCode::fromString('name'));
    }

    public function test_it_should_extract_property_by_name(): void
    {
        self::assertFalse($this->extractor->hasProperty($code = PropertyCode::random()));

        self::assertFalse(
            $this->extractor->visitProperty(
                $code,
                PropertyName::random(),
                $this->createMock(PropertyType::class)
            )
        );

        self::assertTrue($this->extractor->hasProperty($code));
        self::assertInstanceOf(PropertyDefinition::class, $this->extractor->getProperty($code));
    }

    public function test_it_should_extract_property_constraint_by_name(): void
    {
        self::assertFalse($this->extractor->hasPropertyConstraint(
            $code = PropertyCode::random(), 'const')
        );

        $this->extractor->visitProperty(
            $code,
            PropertyName::random(),
            $this->createMock(PropertyType::class)
        );
        $this->extractor->visitPropertyConstraint(
            $code,
            'const',
            $this->createMock(PropertyConstraint::class)
        );

        self::assertTrue($this->extractor->hasPropertyConstraint($code, 'const'));
    }

    public function test_it_should_extract_document_constraint_by_name(): void
    {
        self::assertFalse($this->extractor->hasDocumentConstraint('const'));

        $this->extractor->visitDocumentType(DocumentTypeId::random());
        $this->extractor->visitDocumentConstraint(
            'const',
            $const = $this->createMock(DocumentConstraint::class)
        );

        self::assertTrue($this->extractor->hasDocumentConstraint('const'));
        self::assertSame($const, $this->extractor->getDocumentConstraint('const'));
    }

    public function test_it_should_extract_property_parameter_by_name(): void
    {
        self::assertFalse($this->extractor->hasPropertyParameter($code = PropertyCode::random(), 'param'));

        $this->extractor->visitProperty(
            $code,
            PropertyName::random(),
            $this->createMock(PropertyType::class)
        );
        $this->extractor->visitPropertyParameter(
            $code,
            'param',
            $this->createMock(PropertyParameter::class)
        );

        self::assertTrue($this->extractor->hasPropertyParameter($code, 'param'));
    }
}
