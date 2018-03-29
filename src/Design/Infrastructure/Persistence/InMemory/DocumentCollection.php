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

    /**
     * @param DocumentId $id
     *
     * @return DocumentDesigner
     * @throws EntityNotFoundException
     */
    public function getDocumentByIdentity(DocumentId $id): DocumentDesigner
    {
        if (! isset($this->documents[$id->toString()])) {
            throw EntityNotFoundException::objectWithIdentity($id);
        }

        return $this->documents[$id->toString()];
    }

    /**
     * @param DocumentId $id
     * @param DocumentDesigner $document
     */
    public function saveDocument(DocumentId $id, DocumentDesigner $document)
    {
        $this->documents[$id->toString()] = $document;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->documents);
    }
}
