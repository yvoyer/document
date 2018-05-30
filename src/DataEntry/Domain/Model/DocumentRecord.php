<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Exception\UndefinedProperty;

final class DocumentRecord
{
    /**
     * @var RecordId
     */
    private $id;

    /**
     * @var DocumentSchema
     */
    private $schema;

    /**
     * @var PropertyValue[]
     */
    private $properties = [];

    /**
     * @param RecordId $id
     * @param DocumentSchema $schema
     */
    public function __construct(RecordId $id, DocumentSchema $schema)
    {
        $this->id = $id;
        $this->schema = $schema;
    }

    /**
     * @return RecordId
     */
    public function getIdentity(): RecordId
    {
        return $this->id;
    }

    /**
     * @return DocumentId
     */
    public function getDocumentId(): DocumentId
    {
        return $this->schema->getIdentity();
    }

    /**
     * @param string $propertyName
     * @param mixed $value
     */
    public function setValue(string $propertyName, $value)
    {
        $this->properties[$propertyName] = $this->schema->createValue($propertyName, $value);
    }

    /**
     * @param string $propertyName
     *
     * @return PropertyValue
     */
    public function getValue(string $propertyName): PropertyValue
    {
        if (! $this->hasProperty($propertyName)) {
            throw new UndefinedProperty($propertyName);
        }

        return $this->properties[$propertyName];
    }

    /**
     * @param string $propertyName
     *
     * @return bool
     */
    private function hasProperty(string $propertyName): bool
    {
        return isset($this->properties[$propertyName]);
    }
}
