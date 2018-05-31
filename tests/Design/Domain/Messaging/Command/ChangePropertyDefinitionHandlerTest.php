<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Definition\RequiredProperty;
use Star\Component\Document\Design\Domain\Model\Exception\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\PropertyAttribute;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Tools\DocumentBuilder;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

final class ChangePropertyDefinitionHandlerTest extends TestCase
{
    /**
     * @var ChangePropertyDefinitionHandler
     */
    private $handler;

    /**
     * @var DocumentCollection
     */
    private $documents;

    public function setUp()
    {
        $this->documents = new DocumentCollection();
        $this->handler = new ChangePropertyDefinitionHandler(
            $this->documents
        );
    }

    public function test_it_should_change_the_attribute_of_the_property()
    {
        $name = new PropertyName('section');
        $document = DocumentBuilder::createBuilder('d')
            ->createTextProperty($name->toString())->endProperty()
            ->build();
        $this->documents->saveDocument($document->getIdentity(), $document);

        $this->assertFalse($document->getPropertyDefinition($name->toString())->isRequired());

        $this->handler->__invoke(
            ChangePropertyDefinition::fromString(
                $document->getIdentity()->toString(),
                $name->toString(),
                new RequiredProperty()
            )
        );

        $this->assertTrue($document->getPropertyDefinition($name->toString())->isRequired());
    }

    public function test_it_should_throw_exception_when_property_not_found_in_document()
    {
        $document = DocumentBuilder::createBuilder('d')->build();
        $this->documents->saveDocument($document->getIdentity(), $document);

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->handler->__invoke(
            ChangePropertyDefinition::fromString(
                $document->getIdentity()->toString(),
                'not found',
                $this->createMock(PropertyAttribute::class)
            )
        );
    }
}
