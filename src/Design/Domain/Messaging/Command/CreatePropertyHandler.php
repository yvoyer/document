<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;

final class CreatePropertyHandler
{
    private DocumentTypeRepository $documents;

    public function __construct(DocumentTypeRepository $documents)
    {
        $this->documents = $documents;
    }

    public function __invoke(CreateProperty $command): void
    {
        $document = $this->documents->getDocumentByIdentity($command->typeId());
        $document->addProperty(
            $command->code(),
            $command->name(),
            $command->type(),
            $command->createdAt()
        );

        $this->documents->saveDocument($document);
    }
}
