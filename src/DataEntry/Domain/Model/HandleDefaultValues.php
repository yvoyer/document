<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use function in_array;

final class HandleDefaultValues implements DocumentTypeVisitor
{
    /**
     * @var string[]
     */
    private array $propertiesToExclude;

    /**
     * @var RecordValue[] indexed by property name
     */
    private array $defaultValues = [];

    public function __construct(string ...$propertiesToExclude)
    {
        $this->propertiesToExclude = $propertiesToExclude;
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
        if (false === in_array($code->toString(), $this->propertiesToExclude)) {
            $this->defaultValues[$code->toString()] = new EmptyValue();
        }

        return false;
    }

    public function enterPropertyConstraints(PropertyCode $code): void
    {
    }

    public function visitPropertyConstraint(
        PropertyCode $code,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
    }

    public function enterPropertyParameters(PropertyCode $code): void
    {
    }

    public function visitPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
    }

    /**
     * @return RecordValue[] Indexed by property name
     */
    public function allDefaultValues(): array
    {
        return $this->defaultValues;
    }
}
