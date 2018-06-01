<?php declare(strict_types=1);

namespace Star\Component\Document\Application\Port;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\DocumentSchema;
use Star\Component\Document\DataEntry\Domain\Model\SchemaFactory;
use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class DocumentDesignerToSchema implements SchemaFactory
{
    /**
     * @var DocumentRepository
     */
    private $documents;

    /**
     * @param DocumentRepository $documents
     */
    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }

    /**
     * @param DocumentId $documentId
     *
     * @return DocumentSchema
     */
    public function createSchema(DocumentId $documentId): DocumentSchema
    {
        return new DesigningToDataEntry(
            $this->documents->getDocumentByIdentity($documentId)
        );
    }
}
