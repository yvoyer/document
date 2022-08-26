<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Audit\Domain\Model\UpdatedBy;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyConstraintWasRemoved implements DocumentTypeEvent
{
    private DocumentTypeId $document;
    private PropertyCode $propertyCode;
    private string $constraintName;

    public function __construct(
        DocumentTypeId $document,
        PropertyCode $propertyCode,
        string $constraintName
    ) {
        $this->document = $document;
        $this->propertyCode = $propertyCode;
        $this->constraintName = $constraintName;
    }

    final public function typeId(): DocumentTypeId
    {
        return $this->document;
    }

    final public function propertyCode(): PropertyCode
    {
        return $this->propertyCode;
    }

    final public function constraintName(): string
    {
        return $this->constraintName;
    }

    final public function updatedAt(): AuditDateTime
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    final public function updatedBy(): UpdatedBy
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
