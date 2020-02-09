<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Validation;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

final class ValidateConstraints implements DocumentVisitor
{
    /**
     * @var PropertyName
     */
    private $property;

    /**
     * @var RecordValue
     */
    private $value;

    /**
     * @var ErrorList
     */
    private $errors;

    public function __construct(
        string $property,
        RecordValue $value,
        ErrorList $errors
    ) {
        $this->property = PropertyName::fromString($property);
        $this->value = $value;
        $this->errors = $errors;
    }

    public function visitDocument(DocumentId $id): void
    {
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        return ! $name->matchName($this->property);
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $constraint->validate($propertyName->toString(), $this->value, $this->errors);
    }

    public function visitValueTransformer(
        PropertyName $propertyName,
        string $constraintName,
        TransformerIdentifier $identifier
    ): void {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
