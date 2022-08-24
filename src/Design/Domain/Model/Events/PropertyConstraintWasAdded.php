<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyConstraintWasAdded implements DocumentTypeEvent
{
    private DocumentTypeId $document;
    private PropertyCode $propertyCode;
    private string $constraintAlias;
    private ConstraintData $constraintData;
    private DocumentOwner $addedBy;
    private AuditDateTime $addedAt;

    public function __construct(
        DocumentTypeId $document,
        PropertyCode $propertyCode,
        string $constraintAlias,
        ConstraintData $constraintData,
        DocumentOwner $addedBy,
        AuditDateTime $addedAt
    ) {
        $this->document = $document;
        $this->propertyCode = $propertyCode;
        $this->constraintAlias = $constraintAlias;
        $this->constraintData = $constraintData;
        $this->addedBy = $addedBy;
        $this->addedAt = $addedAt;
    }

    public function typeId(): DocumentTypeId
    {
        return $this->document;
    }

    public function propertyCode(): PropertyCode
    {
        return $this->propertyCode;
    }

    public function constraintAlias(): string
    {
        return $this->constraintAlias;
    }

    public function constraintData(): ConstraintData
    {
        return $this->constraintData;
    }

    final public function updatedBy(): DocumentOwner
    {
        return $this->addedBy;
    }

    final public function updatedAt(): AuditDateTime
    {
        return $this->addedAt;
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        \var_dump($payload);
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
