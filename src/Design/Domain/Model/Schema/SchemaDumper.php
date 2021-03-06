<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class SchemaDumper implements DocumentVisitor
{
    const INDEX_ID = 'id';
    const INDEX_TYPE = 'type';
    const INDEX_PROPERTIES = 'properties';
    const INDEX_CONSTRAINTS = 'constraints';
    const INDEX_PARAMETERS = 'parameters';

    /**
     * @var mixed[]
     */
    private $data = [];

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->data;
    }

    public function visitDocument(DocumentId $id): void
    {
        $this->data[self::INDEX_ID] = $id->toString();
        $this->data[self::INDEX_PROPERTIES] = [];
    }

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->data[self::INDEX_PROPERTIES][$name->toString()][self::INDEX_TYPE] = $type->toData()->toArray();

        return false;
    }

    public function enterPropertyConstraints(PropertyName $propertyName): void
    {
        $this->data[self::INDEX_PROPERTIES][$propertyName->toString()][self::INDEX_CONSTRAINTS] = [];
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $property = $propertyName->toString();
        $constraintData = $constraint->toData()->toArray();
        $this->data[self::INDEX_PROPERTIES][$property][self::INDEX_CONSTRAINTS][$constraintName] = $constraintData;
    }

    public function enterPropertyParameters(PropertyName $propertyName): void
    {
        $this->data[self::INDEX_PROPERTIES][$propertyName->toString()][self::INDEX_PARAMETERS] = [];
    }

    public function visitPropertyParameter(
        PropertyName $propertyName,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $parameterData = $parameter->toParameterData()->toArray();
        $this->data[self::INDEX_PROPERTIES][$propertyName->toString()][self::INDEX_PARAMETERS][$parameterName]
            = $parameterData;
    }
}
