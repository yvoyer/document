<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;

final class AddPropertyParameterHandler
{
    private DocumentTypeRepository $documents;

    public function __construct(DocumentTypeRepository $documents)
    {
        $this->documents = $documents;
    }

    public function __invoke(AddPropertyParameter $command): void
    {
        $document = $this->documents->getDocumentByIdentity($command->typeId());
        $document->addPropertyParameter(
            $command->code(),
            $command->parameterName(),
            $command->parameterData()->createParameter(),
            $command->addedAt()
        );

        $this->documents->saveDocument($document);
    }
}
