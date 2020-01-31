<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

final class CreateDocumentHandlerTest extends TestCase
{
    /**
     * @var CreateDocumentHandler
     */
    private $handler;

    /**
     * @var DocumentCollection
     */
    private $documents;

    public function setUp(): void
    {
        $this->handler = new CreateDocumentHandler(
            $this->documents = new DocumentCollection()
        );
    }

    public function test_it_create_a_draft_document(): void
    {
        $this->assertCount(0, $this->documents);
        $id = DocumentId::fromString('id');

        $handler = $this->handler;
        $handler(new CreateDocument($id));

        $this->assertCount(1, $this->documents);
        $document = $this->documents->getDocumentByIdentity($id);
        $this->assertInstanceOf(DocumentDesigner::class, $document);
    }
}
