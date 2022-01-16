<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentHandler;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Test\NullOwner;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

final class CreateDocumentHandlerTest extends TestCase
{
    public function test_it_create_a_draft_document(): void
    {
        $handler = new CreateDocumentHandler(
            $documents = new DocumentCollection()
        );
        $this->assertCount(0, $documents);
        $id = DocumentId::fromString('id');

        $handler(CreateDocument::emptyDocument($id, new NullOwner(), new DateTimeImmutable()));

        $this->assertCount(1, $documents);
        $document = $documents->getDocumentByIdentity($id);
        $this->assertSame('id', $document->getIdentity()->toString());
    }
}
