<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class CreateDocumentHandler
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

    public function __invoke(CreateDocument $command): void
    {
        $document = DocumentAggregate::draft($command->documentId(), $command->type());

        $this->documents->saveDocument($document);
    }
}
