<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\DomainEvent\Messaging\Command;

final class RemovePropertyConstraint implements Command
{
    private DocumentTypeId $documentId;
    private PropertyCode $code;
    private string $constraintName;

    public function __construct(
        DocumentTypeId $documentId,
        PropertyCode $code,
        string $constraintName
    ) {
        $this->documentId = $documentId;
        $this->code = $code;
        $this->constraintName = $constraintName;
    }

    public function documentId(): DocumentTypeId
    {
        return $this->documentId;
    }

    public function code(): PropertyCode
    {
        return $this->code;
    }

    public function constraintName(): string
    {
        return $this->constraintName;
    }
}
