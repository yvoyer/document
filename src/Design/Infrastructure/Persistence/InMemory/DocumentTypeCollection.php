<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\InMemory;

use Countable;
use Star\Component\Document\DataEntry\Domain\Model\SchemaFactory;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\Design\Domain\Model\DocumentTypeAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;
use Star\Component\Identity\Exception\EntityNotFoundException;
use function array_map;

final class DocumentTypeCollection implements DocumentTypeRepository, Countable, SchemaFactory
{
    /**
     * @var DocumentTypeAggregate[]
     */
    private $documents = [];

    public function __construct(DocumentTypeAggregate ...$documents)
    {
        array_map(
            function (DocumentTypeAggregate $document) {
                $this->saveDocument($document);
            },
            $documents
        );
    }

    public function getDocumentByIdentity(DocumentTypeId $id): DocumentTypeAggregate
    {
        if (! isset($this->documents[$id->toString()])) {
            throw EntityNotFoundException::objectWithIdentity($id);
        }

        return $this->documents[$id->toString()];
    }

    public function saveDocument(DocumentTypeAggregate $document): void
    {
        $this->documents[$document->getIdentity()->toString()] = $document;
    }

    public function count(): int
    {
        return count($this->documents);
    }

    public function createSchema(DocumentTypeId $documentId): SchemaMetadata
    {
        return $this->getDocumentByIdentity($documentId)->getSchema();
    }
}
