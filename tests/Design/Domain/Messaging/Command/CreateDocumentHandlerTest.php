<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentType;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentTypeHandler;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\Test\NullOwner;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentTypeCollection;

final class CreateDocumentHandlerTest extends TestCase
{
    public function test_it_create_a_draft_document(): void
    {
        $handler = new CreateDocumentTypeHandler(
            $documents = new DocumentTypeCollection()
        );
        $this->assertCount(0, $documents);
        $id = DocumentTypeId::fromString('id');

        $handler(
            new CreateDocumentType(
                $id,
                DocumentName::random(),
                new NullOwner(),
                new DateTimeImmutable()
            )
        );

        $this->assertCount(1, $documents);
        $document = $documents->getDocumentByIdentity($id);
        $this->assertSame('id', $document->getIdentity()->toString());
    }
}
