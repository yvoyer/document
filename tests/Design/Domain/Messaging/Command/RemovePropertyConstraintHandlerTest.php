<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\RemovePropertyConstraint;
use Star\Component\Document\Design\Domain\Messaging\Command\RemovePropertyConstraintHandler;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentTypeCollection;

final class RemovePropertyConstraintHandlerTest extends TestCase
{
    private RemovePropertyConstraintHandler $handler;
    private DocumentTypeCollection $documents;

    public function setUp(): void
    {
        $this->handler = new RemovePropertyConstraintHandler(
            $this->documents = new DocumentTypeCollection()
        );
    }

    public function test_it_should_remove_constraint(): void
    {
        $document = DocumentTypeBuilder::startDocumentTypeFixture('d')
            ->createText($code = 'text')->required()->endProperty()
            ->getDocumentType();
        $this->documents->saveDocument($document);
        $code = PropertyCode::fromString($code);

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertTrue($visitor->hasPropertyConstraint($code, 'required'));

        $this->handler->__invoke(
            new RemovePropertyConstraint(
                $document->getIdentity(),
                $code,
                'required'
            )
        );

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertFalse($visitor->hasPropertyConstraint($code, 'required'));
    }

    public function test_it_should_throw_exception_when_property_not_found_in_document(): void
    {
        $document = DocumentTypeBuilder::startDocumentTypeFixture('d')->getDocumentType();
        $this->documents->saveDocument($document);

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with code "not-found" could not be found.');
        $this->handler->__invoke(
            new RemovePropertyConstraint(
                $document->getIdentity(),
                PropertyCode::fromString('not found'),
                'const'
            )
        );
    }
}
