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

    public function setUp()
    {
        $this->documents = new DocumentCollection();
        $this->handler = new AddPropertyConstraintHandler($this->documents);
    }

    public function test_it_should_change_the_attribute_of_the_property()
    {
        $name = new PropertyName('section');
        $document = DocumentBuilder::createBuilder('d')
            ->createText($name->toString())->endProperty()
            ->build();
        $this->documents->saveDocument($document->getIdentity(), $document);

        $this->assertFalse($document->getPropertyDefinition($name->toString())->hasConstraint('attribute'));

        $this->handler->__invoke(
            AddPropertyConstraint::fromString(
                $document->getIdentity()->toString(),
                $name->toString(),
                'attribute',
                $this->createMock(PropertyConstraint::class)
            )
        );

        $this->assertTrue($document->getPropertyDefinition($name->toString())->hasConstraint('attribute'));
    }

    public function test_it_should_throw_exception_when_property_not_found_in_document()
    {
        $document = DocumentBuilder::createBuilder('d')->build();
        $this->documents->saveDocument($document->getIdentity(), $document);

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->handler->__invoke(
            AddPropertyConstraint::fromString(
                $document->getIdentity()->toString(),
                'not found',
                'const',
                $this->createMock(PropertyConstraint::class)
            )
        );
    }
}
