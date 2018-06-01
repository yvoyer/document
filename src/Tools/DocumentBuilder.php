<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use Star\Component\Document\Adapter\DocumentDesignerToSchema;
use Star\Component\Document\Application\Port\DefinitionToSchema;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentDesignerAggregate;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Types\BooleanType;
use Star\Component\Document\Design\Domain\Model\Types\DateType;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

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
    public function createTextProperty(string $name): PropertyBuilder
    {
        return $this->createProperty($name, StringType::class);
    }

    /**
     * @param string $name
     *
     * @return PropertyBuilder
     */
    public function createBooleanProperty(string $name): PropertyBuilder
    {
        return $this->createProperty($name, BooleanType::class);
    }

    /**
     * @param string $name
     *
     * @return PropertyBuilder
     */
    public function createDateProperty(string $name): PropertyBuilder
    {
        return $this->createProperty($name, DateType::class);
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
                new DefinitionToSchema($this->document)
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
     * @param string $id
     *
     * @return DocumentBuilder
     */
    public static function createBuilder(string $id): self
    {
        return new self(new DocumentId($id));
    }
}