<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Structure;

use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentId;
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
    /**
     * @var PropertyExtractor
     */
    private $extractor;

    protected function setUp(): void
    {
        $this->extractor = new PropertyExtractor();
    }

    public function test_it_should_throw_exception_when_property_not_found(): void
    {
        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "name" could not be found.');
        $this->extractor->getProperty('name');
    }

    public function test_it_should_extract_property_by_name(): void
    {
        self::assertFalse($this->extractor->hasProperty('name'));

        self::assertFalse(
            $this->extractor->visitProperty(
                PropertyName::fromString('name'),
                $this->createMock(PropertyType::class)
            )
        );

        self::assertTrue($this->extractor->hasProperty('name'));
        self::assertInstanceOf(PropertyDefinition::class, $this->extractor->getProperty('name'));
    }

    public function test_it_should_extract_property_constraint_by_name(): void
    {
        self::assertFalse($this->extractor->hasPropertyConstraint('name', 'const'));

        $this->extractor->visitProperty(
            PropertyName::fromString('name'),
            $this->createMock(PropertyType::class)
        );
        $this->extractor->visitPropertyConstraint(
            PropertyName::fromString('name'),
            'const',
            $this->createMock(PropertyConstraint::class)
        );

        self::assertTrue($this->extractor->hasPropertyConstraint('name', 'const'));
    }

    public function test_it_should_extract_document_constraint_by_name(): void
    {
        self::assertFalse($this->extractor->hasDocumentConstraint('const'));

        $this->extractor->visitDocument(DocumentId::random());
        $this->extractor->visitDocumentConstraint(
            'const',
            $const = $this->createMock(DocumentConstraint::class)
        );

        self::assertTrue($this->extractor->hasDocumentConstraint('const'));
        self::assertSame($const, $this->extractor->getDocumentConstraint('const'));
    }

    public function test_it_should_extract_property_parameter_by_name(): void
    {
        self::assertFalse($this->extractor->hasPropertyParameter('name', 'param'));

        $this->extractor->visitProperty(
            PropertyName::fromString('name'),
            $this->createMock(PropertyType::class)
        );
        $this->extractor->visitPropertyParameter(
            PropertyName::fromString('name'),
            'param',
            $this->createMock(PropertyParameter::class)
        );

        self::assertTrue($this->extractor->hasPropertyParameter('name', 'param'));
    }
}
