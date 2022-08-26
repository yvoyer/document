<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Messaging\Command\CreatePropertyHandler;
use Star\Component\Document\Design\Domain\Model\DocumentTypeAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Types\StringType;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentTypeCollection;
use Star\Component\Identity\Exception\EntityNotFoundException;

final class CreatePropertyHandlerTest extends TestCase
{
    private CreatePropertyHandler $handler;
    private DocumentTypeCollection $documents;
    private DocumentTypeAggregate $document;

    public function setUp(): void
    {
        $this->document = DocumentTypeBuilder::startDocumentTypeFixture()->getDocumentType();
        $this->handler = new CreatePropertyHandler(
            $this->documents = new DocumentTypeCollection()
        );
    }

    public function test_it_should_create_a_property(): void
    {
        $id = $this->document->getIdentity();
        $this->documents->saveDocument($this->document);

        $this->handler->__invoke(
            new CreateProperty(
                $id,
                $code = PropertyCode::random(),
                PropertyName::random(),
                new StringType(),
                AuditDateTime::fromNow()
            )
        );

        $this->document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        $this->assertInstanceOf(PropertyDefinition::class, $visitor->getProperty($code));
    }

    public function test_it_should_throw_exception_when_document_not_found(): void
    {
        $handler = $this->handler;

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage("with identity 'invalid' could not be found.");
        $handler(
            new CreateProperty(
                DocumentTypeId::fromString('invalid'),
                PropertyCode::random(),
                PropertyName::random(),
                new StringType(),
                AuditDateTime::fromNow()
            )
        );
    }
}
