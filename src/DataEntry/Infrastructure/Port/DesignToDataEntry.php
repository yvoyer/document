<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Port;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\DocumentSchema;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerFactory;

final class DesignToDataEntry implements DocumentSchema
{
    /**
     * @var ReadOnlyDocument
     */
    private $document;

    /**
     * @var TransformerFactory
     */
    private $factory;

    /**
     * @param ReadOnlyDocument $document
     * @param TransformerFactory $factory
     */
    public function __construct(ReadOnlyDocument $document, TransformerFactory $factory)
    {
        $this->document = $document;
        $this->factory = $factory;
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
        $definition = $this->document->getPropertyDefinition(PropertyName::fromString($propertyName));
        $definition->validateRawValue($rawValue);
        $type = $definition->getType();

        return $type->createValue(
            $propertyName,
            $definition->transformValue($rawValue, $this->factory)
        );
    }
}
