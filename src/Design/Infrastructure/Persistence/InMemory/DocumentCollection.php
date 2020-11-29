<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\InMemory;

use Countable;
use Star\Component\Document\DataEntry\Domain\Model\SchemaFactory;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\Design\Domain\Model\DocumentAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentRepository;
use Star\Component\Identity\Exception\EntityNotFoundException;
use function array_map;

final class DocumentCollection implements DocumentRepository, Countable, SchemaFactory
{
    /**
     * @var DocumentAggregate[]
     */
    private $documents = [];

    public function __construct(DocumentAggregate ...$documents)
    {
        array_map(
            function (DocumentAggregate $document) {
                $this->saveDocument($document);
            },
            $documents
        );
    }

    public function getDocumentByIdentity(DocumentId $id): DocumentAggregate
    {
        if (! isset($this->documents[$id->toString()])) {
            throw EntityNotFoundException::objectWithIdentity($id);
        }

        return $this->documents[$id->toString()];
    }

    public function saveDocument(DocumentAggregate $document): void
    {
        $this->documents[$document->getIdentity()->toString()] = $document;
    }

    public function count(): int
    {
        return count($this->documents);
    }

    public function createSchema(DocumentId $documentId): SchemaMetadata
    {
        return $this->getDocumentByIdentity($documentId)->getSchema();
    }
}
