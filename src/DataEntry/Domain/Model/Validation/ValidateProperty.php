<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Validation;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class ValidateProperty implements DocumentVisitor
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

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        return ! $name->matchName($this->property);
    }

    public function enterPropertyConstraints(PropertyName $propertyName): void
    {
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $constraint->validate($propertyName->toString(), $this->value, $this->errors);
    }

    public function enterPropertyParameters(PropertyName $propertyName): void
    {
    }

    public function visitPropertyParameter(
        PropertyName $propertyName,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $parameter->validate($propertyName->toString(), $this->value, $this->errors);
    }
}
