<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

final class DocumentSchema
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyType[]
     */
    private $properties = [];

    public function __construct(DocumentId $documentId)
    {
        $this->documentId = $documentId;
    }

    public function getIdentity(): DocumentId
    {
        return $this->documentId;
    }

    public function addProperty(string $name, PropertyType $type): void
    {
        $this->properties[$name] = $type;
    }

    public function getPropertyType(string $name): PropertyType
    {
        if (!\array_key_exists($name, $this->properties)) {
            throw new ReferencePropertyNotFound(PropertyName::fromString($name));
        }

        return $this->properties[$name];
    }

    public function toString(): string
    {
        $data = [];
        $data['id'] = $this->documentId->toString();
        foreach ($this->properties as $name => $property) {
            $data['properties'][$name]['type'] = $property->toString();
        }

        return \json_encode($data);
    }

    public static function fromString(string $string): DocumentSchema
    {
        $data = \json_decode($string, true);
        $schema = new DocumentSchema(DocumentId::fromString($data['id']));
        foreach ($data['properties'] as $name => $property) {
            switch ($property['type']) {
                case (new StringType())->toString():
                    $schema->addText($name);
                    break;

            }
        }

        return $schema;
    }
}
