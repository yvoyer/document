<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use DateTimeInterface;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyConstraintWasAdded implements DocumentEvent
{
    private DocumentId $document;
    private PropertyName $propertyName;
    private string $constraintName;
    private ConstraintData $constraint;
    private DocumentOwner $addedBy;
    private DateTimeInterface $addedAt;

    public function __construct(
        DocumentId $document,
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint,
        DocumentOwner $addedBy,
        DateTimeInterface $addedAt
    ) {
        $this->document = $document;
        $this->propertyName = $propertyName;
        $this->constraintName = $constraintName;
        $this->constraint = $constraint->toData();
        $this->addedBy = $addedBy;
        $this->addedAt = $addedAt;
    }

    public function documentId(): DocumentId
    {
        return $this->document;
    }

    public function propertyName(): PropertyName
    {
        return $this->propertyName;
    }

    public function constraintName(): string
    {
        return $this->constraintName;
    }

    public function constraint(): PropertyConstraint
    {
        return $this->constraint->createPropertyConstraint();
    }

    final public function updatedBy(): DocumentOwner
    {
        return $this->addedBy;
    }

    final public function updatedAt(): DateTimeInterface
    {
        return $this->addedAt;
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        \var_dump($payload);
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
