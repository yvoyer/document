<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\RemovePropertyConstraint;
use Star\Component\Document\Design\Domain\Messaging\Command\RemovePropertyConstraintHandler;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
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
            ->createText($name = 'text')->required()->endProperty()
            ->getDocument();
        $this->documents->saveDocument($document);

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertTrue($visitor->hasPropertyConstraint($name, 'required'));

        $this->handler->__invoke(
            new RemovePropertyConstraint(
                $document->getIdentity(),
                PropertyName::fromString($name),
                'required'
            )
        );

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertFalse($visitor->hasPropertyConstraint($name, 'required'));
    }

    public function test_it_should_throw_exception_when_property_not_found_in_document(): void
    {
        $document = DocumentBuilder::createDocument('d')->getDocument();
        $this->documents->saveDocument($document);

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
