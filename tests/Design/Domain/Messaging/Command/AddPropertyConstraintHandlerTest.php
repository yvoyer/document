<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraint;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraintHandler;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\ConstraintFactory;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentTypeCollection;

final class AddPropertyConstraintHandlerTest extends TestCase
{
    private AddPropertyConstraintHandler $handler;
    private DocumentTypeCollection $documents;

    public function setUp(): void
    {
        $this->documents = new DocumentTypeCollection();
        $this->handler = new AddPropertyConstraintHandler($this->documents);
    }

    public function test_it_should_change_the_attribute_of_the_property(): void
    {
        $document = DocumentTypeBuilder::startDocumentTypeFixture('d')
            ->createText($code = 'code')->endProperty()
            ->getDocumentType();
        $this->documents->saveDocument($document);
        $code = PropertyCode::fromString($code);

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertFalse($visitor->hasPropertyConstraint($code, 'const'));

        $this->handler->__invoke(
            new AddPropertyConstraint(
                $document->getIdentity(),
                $code,
                'const',
                new NoConstraint(),
                AuditDateTime::fromNow()
            )
        );

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertTrue($visitor->hasPropertyConstraint($code, 'const'));
    }

    public function test_it_should_throw_exception_when_property_not_found_in_document(): void
    {
        $document = DocumentTypeBuilder::startDocumentTypeFixture('d')->getDocumentType();
        $this->documents->saveDocument($document);

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with code "not-found" could not be found.');
        $this->handler->__invoke(
            new AddPropertyConstraint(
                $document->getIdentity(),
                PropertyCode::fromString('not found'),
                'const',
                new NoConstraint(),
                AuditDateTime::fromNow()
            )
        );
    }
}
