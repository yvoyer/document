<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Port;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\DocumentSchema;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
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
     * @param StrategyToHandleValidationErrors $strategy
     *
     * @return RecordValue
     */
    public function createValue(
        string $propertyName,
        $rawValue,
        StrategyToHandleValidationErrors $strategy
    ): RecordValue {
        $name = PropertyName::fromString($propertyName);
        $definition = $this->document->getPropertyDefinition($name);
        $definition->validateRawValue($rawValue, $errors = new ErrorList());
        $type = $definition->getType();

        if ($errors->hasErrors()) {
            $strategy->handleFailure($errors);
        }

        return $type->createValue(
            $propertyName,
            $definition->transformValue($rawValue, $this->factory)
        );
    }
}
