<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentTypeAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;

final class CreateDocumentTypeHandler
{
    private DocumentTypeRepository $types;

    public function __construct(DocumentTypeRepository $documents)
    {
        $this->types = $documents;
    }

    public function __invoke(CreateDocumentType $command): void
    {
        $document = DocumentTypeAggregate::draft(
            $command->typeId(),
            $command->name(),
            $command->owner(),
            $command->createdAt()
        );

        $this->types->saveDocument($document);
    }
}
