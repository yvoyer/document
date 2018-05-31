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

    public function setUp()
    {
        $this->handler = new CreateDocumentHandler(
            $this->documents = new DocumentCollection()
        );
    }

    public function test_it_create_a_draft_document()
    {
        $this->assertCount(0, $this->documents);
        $id = new DocumentId('id');

        $handler = $this->handler;
        $handler(CreateDocument::fromString($id->toString()));

        $this->assertCount(1, $this->documents);
        $document = $this->documents->getDocumentByIdentity($id);
        $this->assertInstanceOf(DocumentDesigner::class, $document);
    }
}
