<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;

final class RenameDocumentTypeHandler
{
    private DocumentTypeRepository $types;

    public function __construct(DocumentTypeRepository $types)
    {
        $this->types = $types;
    }

    public function __invoke(RenameDocumentType $command): void
    {
        $type = $this->types->getDocumentByIdentity($command->typeId());
        $type->rename($command->name(), $command->renamedAt(), $command->renamedBy());

        $this->types->saveDocument($type);
    }
}
