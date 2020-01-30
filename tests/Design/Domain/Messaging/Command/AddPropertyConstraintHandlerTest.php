<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Exception\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;
use Star\Component\Document\Tools\DocumentBuilder;

final class AddPropertyConstraintHandlerTest extends TestCase
{
    /**
     * @var AddPropertyConstraintHandler
     */
    private $handler;

    /**
     * @var DocumentCollection
     */
    private $documents;

    public function setUp(): void
    {
        $this->documents = new DocumentCollection();
        $this->handler = new AddPropertyConstraintHandler($this->documents);
    }

    public function test_it_should_change_the_attribute_of_the_property(): void
    {
        $name = PropertyName::fromString('section');
        $document = DocumentBuilder::createDocument('d')
            ->createText($name->toString())->endProperty()
            ->getDocument();
        $this->documents->saveDocument($document->getIdentity(), $document);

        $this->assertFalse($document->getPropertyDefinition($name)->hasConstraint('attribute'));

        $this->handler->__invoke(
            new AddPropertyConstraint(
                $document->getIdentity(),
                $name,
                'attribute',
                $this->createMock(PropertyConstraint::class)
            )
        );

        $this->assertTrue($document->getPropertyDefinition($name)->hasConstraint('attribute'));
    }

    public function test_it_should_throw_exception_when_property_not_found_in_document(): void
    {
        $document = DocumentBuilder::createDocument('d')->getDocument();
        $this->documents->saveDocument($document->getIdentity(), $document);

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->handler->__invoke(
            new AddPropertyConstraint(
                $document->getIdentity(),
                PropertyName::fromString('not found'),
                'const',
                $this->createMock(PropertyConstraint::class)
            )
        );
    }
}
