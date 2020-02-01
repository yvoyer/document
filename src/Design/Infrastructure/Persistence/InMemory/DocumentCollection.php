<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\InMemory;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentRepository;
use Star\Component\Identity\Exception\EntityNotFoundException;

final class DocumentCollection implements DocumentRepository, \Countable
{
    /**
     * @var DocumentDesigner[]
     */
    private $documents = [];

    public function __construct(DocumentDesigner ...$documents)
    {
        \array_map(
            function (DocumentDesigner $document) {
                $this->saveDocument($document->getIdentity(), $document);
            },
            $documents
        );
    }

    public function getDocumentByIdentity(DocumentId $id): DocumentDesigner
    {
        if (! isset($this->documents[$id->toString()])) {
            throw EntityNotFoundException::objectWithIdentity($id);
        }

        return $this->documents[$id->toString()];
    }

    public function saveDocument(DocumentId $id, DocumentDesigner $document): void
    {
        $this->documents[$id->toString()] = $document;
    }

    public function count(): int
    {
        return count($this->documents);
    }
}
