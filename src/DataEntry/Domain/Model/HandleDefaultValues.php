<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use function array_search;

final class HandleDefaultValues implements DocumentVisitor
{
    /**
     * @var string[]
     */
    private $propertiesToExclude;

    /**
     * @var RecordValue[] indexed by property name
     */
    private $defaultValues = [];

    public function __construct(string ...$propertiesToExclude)
    {
        $this->propertiesToExclude = $propertiesToExclude;
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
        if (false === array_search($name->toString(), $this->propertiesToExclude)) {
            $this->defaultValues[$name->toString()] = new EmptyValue();
        }

        return false;
    }

    public function enterPropertyConstraints(PropertyName $propertyName): void
    {
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
    }

    public function enterPropertyParameters(PropertyName $propertyName): void
    {
    }

    public function visitPropertyParameter(
        PropertyName $propertyName,
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
