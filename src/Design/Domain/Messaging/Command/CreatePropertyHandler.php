<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class CreatePropertyHandler
{
    private DocumentRepository $documents;

    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }

    public function __invoke(CreateProperty $command): void
    {
        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->addProperty(
            $command->name(),
            $command->type(),
            $command->createdBy(),
            $command->createdAt()
        );

        $this->documents->saveDocument($document);
    }
}
