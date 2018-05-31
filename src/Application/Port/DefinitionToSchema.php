<?php declare(strict_types=1);

namespace Star\Component\Document\Application\Port;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\DocumentSchema;
use Star\Component\Document\DataEntry\Domain\Model\PropertyValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
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
        // todo use to ensure requirements $definition =
        // todo $this->document->getPropertyDefinition(new PropertyName($propertyName));
        if (is_string($rawValue)) {
            return new StringValue($rawValue);
        }

        throw new \RuntimeException('Raw value is not supported yet.');
    }
}
