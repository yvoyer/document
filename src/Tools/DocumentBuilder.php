<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Infrastructure\Port\DesignToDataEntry;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentDesignerAggregate;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerFactory;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerRegistry;
use Star\Component\Document\Design\Domain\Model\Types;
use Star\Component\Document\Design\Domain\Model\Values\ListOptionValue;

final class DocumentBuilder
{
    /**
     * @var DocumentId
     */
    private $id;

    /**
     * @var DocumentDesignerAggregate
     */
    private $document;

    /**
     * @var TransformerRegistry
     */
    private $factory;

    private function __construct(DocumentId $id)
    {
        $this->id = $id;
        $this->document = DocumentDesignerAggregate::draft($id);
        $this->factory = new TransformerRegistry();
    }

    public function createText(string $name): PropertyBuilder
    {
        return $this->createProperty($name, new Types\StringType());
    }

    public function createBoolean(string $name): PropertyBuilder
    {
        return $this->createProperty($name, new Types\BooleanType());
    }

    public function createDate(string $name): PropertyBuilder
    {
        return $this->createProperty($name, new Types\DateType());
    }

    public function createNumber(string $name): PropertyBuilder
    {
        return $this->createProperty($name, new Types\NumberType());
    }

    public function createCustomList(string $name, string ...$options): PropertyBuilder
    {
        return $this->createProperty(
            $name,
            new Types\CustomListType(
                ...\array_map(
                    function (int $key) use ($options) {
                        return ListOptionValue::withValueAsLabel($key + 1, $options[$key]);
                    },
                    \array_keys($options)
                )
            )
        );
    }

    public function startRecord(string $recordId): RecordBuilder
    {
        return new RecordBuilder(
            new RecordAggregate(
                new RecordId($recordId),
                new DesignToDataEntry($this->document, $this->factory)
            ),
            $this
        );
    }

    public function withConstraint(DocumentConstraint $constraint): self
    {
        $this->document->setDocumentConstraint($constraint);

        return $this;
    }

    public function getDocument(): DocumentDesigner
    {
        return $this->document;
    }

    private function loadProperty(PropertyName $name): PropertyBuilder
    {
        return new PropertyBuilder($name, $this->document, $this, $this->factory);
    }

    private function createProperty(string $name, PropertyType $type): PropertyBuilder
    {
        $this->document->addProperty(
            $name = PropertyName::fromString($name),
            $type,
            new NoConstraint()
        );

        return $this->loadProperty($name);
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
