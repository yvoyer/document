<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use function self;

final class SchemaDumper implements DocumentTypeVisitor
{
    const INDEX_ID = 'id';
    const INDEX_DEFAULT_LOCALE = 'default_locale';
    const INDEX_PROPERTY_TYPE = 'type';
    const INDEX_PROPERTY_NAME = 'name';
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

    public function visitDocumentType(DocumentTypeId $id): void
    {
        $this->data[self::INDEX_ID] = $id->toString();
        $this->data[self::INDEX_PROPERTIES] = [];
    }

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function visitProperty(PropertyCode $code, PropertyName $name, PropertyType $type): bool
    {
        $this->data[self::INDEX_PROPERTIES][$code->toString()][self::INDEX_PROPERTY_NAME] = $name->toSerializableString();
        $this->data[self::INDEX_PROPERTIES][$code->toString()][self::INDEX_PROPERTY_TYPE] = $type->toData()->toArray();

        return false;
    }

    public function enterPropertyConstraints(PropertyCode $code): void
    {
        $this->data[self::INDEX_PROPERTIES][$code->toString()][self::INDEX_CONSTRAINTS] = [];
    }

    public function visitPropertyConstraint(
        PropertyCode $code,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $property = $code->toString();
        $constraintData = $constraint->toData()->toArray();
        $this->data[self::INDEX_PROPERTIES][$property][self::INDEX_CONSTRAINTS][$constraintName] = $constraintData;
    }

    public function enterPropertyParameters(PropertyCode $code): void
    {
        $this->data[self::INDEX_PROPERTIES][$code->toString()][self::INDEX_PARAMETERS] = [];
    }

    public function visitPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $parameterData = $parameter->toParameterData()->toArray();
        $this->data[self::INDEX_PROPERTIES][$code->toString()][self::INDEX_PARAMETERS][$parameterName]
            = $parameterData;
    }
}
