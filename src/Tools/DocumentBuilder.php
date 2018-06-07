<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use Star\Component\Document\Application\Port\DesigningToDataEntry;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentDesignerAggregate;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Types;

final class DocumentBuilder
{
    /**
     * @var DocumentId
     */
    private $id;

    /**
     * @var DocumentDesigner
     */
    private $document;

    /**
     * @param DocumentId $id
     */
    private function __construct(DocumentId $id)
    {
        $this->id = $id;
        $this->document = new DocumentDesignerAggregate($id);
    }

    /**
     * @param string $name
     *
     * @return PropertyBuilder
     */
    public function createText(string $name): PropertyBuilder
    {
        return $this->createProperty($name, Types\StringType::class);
    }

    /**
     * @param string $name
     *
     * @return PropertyBuilder
     */
    public function createBoolean(string $name): PropertyBuilder
    {
        return $this->createProperty($name, Types\BooleanType::class);
    }

    /**
     * @param string $name
     *
     * @return PropertyBuilder
     */
    public function createDate(string $name): PropertyBuilder
    {
        return $this->createProperty($name, Types\DateType::class);
    }

    /**
     * @param string $name
     *
     * @return PropertyBuilder
     */
    public function createNumber(string $name): PropertyBuilder
    {
        return $this->createProperty($name, Types\NumberType::class);
    }

    /**
     * @param string $name
     * @param string[] $options
     *
     * @return PropertyBuilder
     */
    public function createCustomList(string $name, array $options): PropertyBuilder
    {
        $definition = new PropertyDefinition($name, new Types\CustomListType($options));
        $this->document->createProperty($definition);

        return new PropertyBuilder($definition, $this->document, $this);
    }

    /**
     * @param string $recordId
     *
     * @return RecordBuilder
     */
    public function startRecord(string $recordId): RecordBuilder
    {
        return new RecordBuilder(
            new RecordAggregate(
                new RecordId($recordId),
                new DesigningToDataEntry($this->document)
            ),
            $this
        );
    }

    /**
     * @return DocumentDesigner
     */
    public function build(): DocumentDesigner
    {
        return $this->document;
    }

    /**
     * @param string $name
     * @param string $type
     *
     * @return PropertyBuilder
     */
    private function createProperty(string $name, string $type): PropertyBuilder
    {
        $definition = PropertyDefinition::fromString($name, $type);
        $this->document->createProperty($definition);

        return new PropertyBuilder($definition, $this->document, $this);
    }

    /**
     * @return ConstraintBuilder
     */
    public static function constraints(): ConstraintBuilder
    {
        return new ConstraintBuilder();
    }

    /**
     * @param string $id
     *
     * @return DocumentBuilder
     */
    public static function createBuilder(string $id): self
    {
        return new self(new DocumentId($id));
    }
}
