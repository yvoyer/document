<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\DomainEvent\Messaging\Command;

final class AddPropertyConstraint implements Command
{
    private DocumentTypeId $typeId;
    private PropertyCode $propertyCode;
    private string $constraintCode;
    private ConstraintData $constraintData;
    private AuditDateTime $addedAt;

    public function __construct(
        DocumentTypeId $typeId,
        PropertyCode $propertyCode,
        string $constraintCode,
        PropertyConstraint $constraint,
        AuditDateTime $addedAt
    ) {
        $this->typeId = $typeId;
        $this->propertyCode = $propertyCode;
        $this->constraintCode = $constraintCode;
        $this->constraintData = $constraint->toData();
        $this->addedAt = $addedAt;
    }

    public function typeId(): DocumentTypeId
    {
        return $this->typeId;
    }

    public function propertyCode(): PropertyCode
    {
        return $this->propertyCode;
    }

    public function constraintCode(): string
    {
        return $this->constraintCode;
    }

    public function constraintData(): ConstraintData
    {
        return $this->constraintData;
    }

    final public function addedAt(): AuditDateTime
    {
        return $this->addedAt;
    }
}
