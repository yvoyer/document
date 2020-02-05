<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\ReferencePropertyNotFound;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

final class RemovePropertyConstraintHandlerTest extends TestCase
{
    /**
     * @var RemovePropertyConstraintHandler
     */
    private $handler;

    /**
     * @var DocumentCollection
     */
    private $documents;

    public function setUp(): void
    {
        $this->handler = new RemovePropertyConstraintHandler(
            $this->documents = new DocumentCollection()
        );
    }

    public function test_it_should_remove_constraint(): void
    {
        $document = DocumentBuilder::createDocument('d')
            ->createText('text')->required()->endProperty()
            ->getDocument();
        $this->documents->saveDocument($document->getIdentity(), $document);
        $name = PropertyName::fromString('text');

        $this->assertTrue($document->getPropertyDefinition($name)->hasConstraint('required'));

        $this->handler->__invoke(
            new RemovePropertyConstraint(
                $document->getIdentity(),
                $name,
                'required'
            )
        );

        $this->assertFalse($document->getPropertyDefinition($name)->hasConstraint('required'));
    }

    public function test_it_should_throw_exception_when_property_not_found_in_document(): void
    {
        $document = DocumentBuilder::createDocument('d')->getDocument();
        $this->documents->saveDocument($document->getIdentity(), $document);

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->handler->__invoke(
            new RemovePropertyConstraint(
                $document->getIdentity(),
                PropertyName::fromString('not found'),
                'const'
            )
        );
    }
}
