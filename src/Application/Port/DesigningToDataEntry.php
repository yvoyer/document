<?php declare(strict_types=1);

namespace Star\Component\Document\Application\Port;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\DocumentSchema;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\ReadOnlyDocument;

// todo rename to DefinitionSchema
final class DesigningToDataEntry implements DocumentSchema
{
    /**
     * @var ReadOnlyDocument
     */
    private $document;

    /**
     * @param ReadOnlyDocument $document
     */
    public function __construct(ReadOnlyDocument $document)
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
     * @return RecordValue
     */
    public function createValue(string $propertyName, $rawValue): RecordValue
    {
        $definition = $this->document->getPropertyDefinition($propertyName);
        $definition->validateRawValue($rawValue);
        $type = $definition->getType();

        return $type->createValue($propertyName, $rawValue);
    }
}
