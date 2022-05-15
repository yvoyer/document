<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Messaging\Command\CreatePropertyHandler;
use Star\Component\Document\Design\Domain\Model\DocumentAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Test\NullOwner;
use Star\Component\Document\Design\Domain\Model\Types\StringType;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;
use Star\Component\Identity\Exception\EntityNotFoundException;

final class CreatePropertyHandlerTest extends TestCase
{
    private CreatePropertyHandler $handler;
    private DocumentCollection $documents;
    private DocumentAggregate $document;

    public function setUp(): void
    {
        $this->document = DocumentBuilder::createDocument()->getDocument();
        $this->handler = new CreatePropertyHandler(
            $this->documents = new DocumentCollection()
        );
    }

    public function test_it_should_create_a_property(): void
    {
        $id = $this->document->getIdentity();
        $this->documents->saveDocument($this->document);

        $this->handler->__invoke(
            new CreateProperty(
                $id,
                PropertyName::fromString($name = 'name', 'en'),
                new StringType(),
                new NullOwner(),
                new DateTimeImmutable()
            )
        );

        $this->document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        $this->assertInstanceOf(PropertyDefinition::class, $visitor->getProperty($name));
    }

    public function test_it_should_throw_exception_when_document_not_found(): void
    {
        $handler = $this->handler;

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage("with identity 'invalid' could not be found.");
        $handler(
            new CreateProperty(
                DocumentId::fromString('invalid'),
                PropertyName::fromString('name', 'en'),
                new StringType(),
                new NullOwner(),
                new DateTimeImmutable()
            )
        );
    }
}
