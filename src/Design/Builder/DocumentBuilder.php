<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Builder\RecordBuilder;
use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\Validation\AlwaysThrowExceptionOnValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentAggregate;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerRegistry;
use Star\Component\Document\Design\Domain\Model\Types;
use Star\Component\Document\Design\Domain\Model\Values\ListOptionValue;
use Star\Component\Document\Design\Domain\Model\Values\OptionListValue;

final class DocumentBuilder
{
    /**
     * @var DocumentId
     */
    private $id;

    /**
     * @var DocumentAggregate
     */
    private $document;

    /**
     * @var TransformerRegistry
     */
    private $factory;

    /**
     * @var StrategyToHandleValidationErrors
     */
    private $strategy;

    private function __construct(DocumentId $id)
    {
        $this->id = $id;
        $this->document = DocumentAggregate::draft($id);
        $this->factory = new TransformerRegistry();
        $this->strategy = new AlwaysThrowExceptionOnValidationErrors();
    }

    public function createText(string $name): StringBuilder
    {
        return $this->createProperty($name, new Types\StringType(), StringBuilder::class);
    }

    public function createBoolean(string $name): BooleanBuilder
    {
        return $this->createProperty($name, new Types\BooleanType(), BooleanBuilder::class);
    }

    public function createDate(string $name): DateBuilder
    {
        return $this->createProperty($name, new Types\DateType(), DateBuilder::class);
    }

    public function createNumber(string $name): NumberBuilder
    {
        return $this->createProperty($name, new Types\NumberType(), NumberBuilder::class);
    }

    public function createCustomList(string $name, string ...$options): CustomListBuilder
    {
        return $this->createProperty(
            $name,
            new Types\CustomListType(
                'list',
                OptionListValue::fromArray(
                    \array_map(
                        function (int $key) use ($options) {
                            return ListOptionValue::withValueAsLabel($key + 1, $options[$key]);
                        },
                        \array_keys($options)
                    )
                )
            ),
            CustomListBuilder::class
        );
    }

    public function startRecord(RecordId $recordId = null): RecordBuilder
    {
        if (!$recordId) {
            $recordId= RecordId::random();
        }

        return new RecordBuilder(
            RecordAggregate::withoutValues($recordId, $this->document->getSchema()),
            $this
        );
    }

    public function withConstraint(DocumentConstraint $constraint): self
    {
        $this->document->setDocumentConstraint($constraint);

        return $this;
    }

    public function getErrorStrategyHandler(): StrategyToHandleValidationErrors
    {
        return $this->strategy;
    }

    public function getDocument(): DocumentDesigner
    {
        return $this->document;
    }

    public function createProperty(string $name, PropertyType $type, string $builderClass): PropertyBuilder
    {
        $this->document->addProperty(
            $name = PropertyName::fromString($name),
            $type,
            new NoConstraint()
        );

        /**
         * @var PropertyBuilder $builderClass
         */
        return new $builderClass($name, $this->document, $this, $this->factory);
    }

    public static function constraints(): ConstraintBuilder
    {
        return new ConstraintBuilder();
    }

    public static function createDocument(string $id = null): self
    {
        if (null === $id) {
            $id = DocumentId::random()->toString();
        }

        return new self(DocumentId::fromString($id));
    }
}
