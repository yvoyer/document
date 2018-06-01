<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Exception\ReferencePropertyNotFound;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;
use Star\Component\Document\Tools\DocumentBuilder;

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

    public function setUp()
    {
        $this->handler = new RemovePropertyConstraintHandler(
            $this->documents = new DocumentCollection()
        );
    }

    public function test_it_should_remove_constraint()
    {
        $document = DocumentBuilder::createBuilder('d')
            ->createText('text')->required()->endProperty()
            ->build();
        $this->documents->saveDocument($document->getIdentity(), $document);

        $this->assertTrue($document->getPropertyDefinition('text')->hasConstraint('required'));

        $this->handler->__invoke(
            RemovePropertyConstraint::fromString(
                $document->getIdentity()->toString(),
                'text',
                'required'
            )
        );

        $this->assertFalse($document->getPropertyDefinition('text')->hasConstraint('required'));
    }

    public function test_it_should_throw_exception_when_property_not_found_in_document()
    {
        $document = DocumentBuilder::createBuilder('d')->build();
        $this->documents->saveDocument($document->getIdentity(), $document);

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->handler->__invoke(
            RemovePropertyConstraint::fromString(
                $document->getIdentity()->toString(),
                'not found',
                'const'
            )
        );
    }
}
