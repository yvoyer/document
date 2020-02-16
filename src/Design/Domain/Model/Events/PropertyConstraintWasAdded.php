<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;

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
        $this->document = $document;
        $this->propertyName = $propertyName;
        $this->constraintName = $constraintName;
        $this->constraint = $constraint;
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
        return $this->constraint;
    }
}
