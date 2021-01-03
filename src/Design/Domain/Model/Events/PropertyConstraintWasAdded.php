<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyConstraintWasAdded implements DocumentEvent
{
    /**
     * @var DocumentId
     */
    private $document;

    /**
     * @var PropertyName
     */
    private $propertyName;

    /**
     * @var string
     */
    private $constraintName;

    /**
     * @var PropertyConstraint
     */
    private $constraint;

    public function __construct(
        DocumentId $document,
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ) {
        $this->document = $document->toString();
        $this->propertyName = $propertyName->toString();
        $this->constraintName = $constraintName;
        $this->constraint = $constraint->toData()->toString();
    }

    public function documentId(): DocumentId
    {
        return DocumentId::fromString($this->document);
    }

    public function propertyName(): PropertyName
    {
        return PropertyName::fromString($this->propertyName);
    }

    public function constraintName(): string
    {
        return $this->constraintName;
    }

    public function constraint(): PropertyConstraint
    {
        return ConstraintData::fromString($this->constraint)->createPropertyConstraint();
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        \var_dump($payload);
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
