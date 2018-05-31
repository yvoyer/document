<?php declare(strict_types=1);

namespace Star\Component\Document\Application\Port;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Common\Domain\Model\PropertyValue;
use Star\Component\Document\DataEntry\Domain\Model\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;

final class DefinitionToSchema implements DocumentSchema
{
    /**
     * @var DocumentDesigner
     */
    private $document;

    /**
     * @param DocumentDesigner $document
     */
    public function __construct(DocumentDesigner $document)
    {
        $this->document = $document;
    }

    /**
     * @return DocumentId
     */
    public function getIdentity(): DocumentId
    {
        return $this->document->getIdentity();
    }

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     *
     * @return PropertyValue
     */
    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        $definition = $this->document->getPropertyDefinition($propertyName);
        $propertyType = $definition->getType();

        return $propertyType->createValue($propertyName, $rawValue);
    }
}
