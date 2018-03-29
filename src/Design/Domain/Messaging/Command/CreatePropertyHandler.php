<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class CreatePropertyHandler
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
     * @param CreateProperty $command
     */
    public function __invoke(CreateProperty $command)
    {
        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->createProperty($command->name(), $command->value());

        $this->documents->saveDocument($command->documentId(), $document);
    }
}
