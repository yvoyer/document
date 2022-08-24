<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Infrastructure\Persistence\InMemory;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentTypeCollection;
use Star\Component\Identity\Exception\EntityNotFoundException;

final class DocumentCollectionTest extends TestCase
{
    private DocumentTypeCollection $collection;

    public function setUp(): void
    {
        $this->collection = new DocumentTypeCollection();
    }

    public function test_it_should_save_the_document_type(): void
    {
        $this->assertCount(0, $this->collection);

        $type = DocumentTypeBuilder::startDocumentTypeFixture()->getDocumentType();
        $id = $type->getIdentity();
        $this->collection->saveDocument($type);

        $this->assertCount(1, $this->collection);
        $this->assertSame($type, $this->collection->getDocumentByIdentity($id));
    }

    public function test_it_should_throw_exception_when_not_found(): void
    {
        $id = DocumentTypeId::fromString('not-found');
        $this->assertCount(0, $this->collection);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(EntityNotFoundException::objectWithIdentity($id)->getMessage());
        $this->collection->getDocumentByIdentity($id);
    }
}
