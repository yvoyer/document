<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Port;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\DocumentSchema;
use Star\Component\Document\DataEntry\Domain\Model\SchemaFactory;
use Star\Component\Document\Design\Domain\Model\DocumentRepository;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerFactory;

final class DocumentDesignerToSchema implements SchemaFactory
{
    /**
     * @var DocumentRepository
     */
    private $documents;

    /**
     * @var TransformerFactory
     */
    private $factory;

    /**
     * @param DocumentRepository $documents
     * @param TransformerFactory $factory
     */
    public function __construct(DocumentRepository $documents, TransformerFactory $factory)
    {
        $this->documents = $documents;
        $this->factory = $factory;
    }

    /**
     * @param DocumentId $documentId
     *
     * @return DocumentSchema
     */
    public function createSchema(DocumentId $documentId): DocumentSchema
    {
        return new DesignToDataEntry(
            $this->documents->getDocumentByIdentity($documentId),
            $this->factory
        );
    }
}
