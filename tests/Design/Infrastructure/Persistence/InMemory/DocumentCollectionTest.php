<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Infrastructure\Persistence\InMemory;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;
use Star\Component\Document\Tests\Design\Domain\Model\TestDocument;
use Star\Component\Identity\Exception\EntityNotFoundException;

final class DocumentCollectionTest extends TestCase
{
    /**
     * @var DocumentCollection
     */
    private $collection;

    public function setUp(): void
    {
        $this->collection = new DocumentCollection();
    }

    public function test_it_should_save_the_document(): void
    {
        $this->assertCount(0, $this->collection);

        $document = TestDocument::fixture();
        $id = $document->getIdentity();
        $this->collection->saveDocument($document);

        $this->assertCount(1, $this->collection);
        $this->assertSame($document, $this->collection->getDocumentByIdentity($id));
    }

    public function test_it_should_throw_exception_when_not_found(): void
    {
        $id = DocumentId::fromString('not-found');
        $this->assertCount(0, $this->collection);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(EntityNotFoundException::objectWithIdentity($id)->getMessage());
        $this->collection->getDocumentByIdentity($id);
    }
}
