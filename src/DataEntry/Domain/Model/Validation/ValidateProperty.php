<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Validation;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class ValidateProperty implements DocumentTypeVisitor
{
    private PropertyCode $property;
    private RecordValue $value;
    private ErrorList $errors;

    public function __construct(
        PropertyCode $property,
        RecordValue $value,
        ErrorList $errors
    ) {
        $this->property = $property;
        $this->value = $value;
        $this->errors = $errors;
    }

    public function visitDocumentType(DocumentTypeId $id): void
    {
    }

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function visitProperty(PropertyCode $code, PropertyName $name, PropertyType $type): bool
    {
        return ! $code->matchCode($this->property);
    }

    public function enterPropertyConstraints(PropertyCode $code): void
    {
    }

    public function visitPropertyConstraint(
        PropertyCode $code,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $constraint->validate($code->toString(), $this->value, $this->errors);
    }

    public function enterPropertyParameters(PropertyCode $code): void
    {
    }

    public function visitPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $parameter->validate($code->toString(), $this->value, $this->errors);
    }
}
