<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\InMemory;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Identity\Exception\EntityNotFoundException;

final class DocumentCollectionTest extends TestCase
{
    /**
     * @var DocumentCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new DocumentCollection();
    }

    public function test_it_should_save_the_document()
    {
        $this->assertCount(0, $this->collection);

        $this->collection->saveDocument(
            $id = new DocumentId('d1'),
            $document = $this->createMock(DocumentDesigner::class)
        );

        $this->assertCount(1, $this->collection);
        $this->assertSame($document, $this->collection->getDocumentByIdentity($id));
    }

    public function test_it_should_throw_exception_when_not_found()
    {
        $id = new DocumentId('not-found');
        $this->assertCount(0, $this->collection);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(EntityNotFoundException::objectWithIdentity($id)->getMessage());
        $this->collection->getDocumentByIdentity($id);
    }
}
