<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Tools;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentDesignerAggregate;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Types\BooleanType;
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
        $definition = PropertyDefinition::fromString($name, StringType::class);
        $this->document->createProperty($definition);

        return new PropertyBuilder($definition, $this->document, $this);
    }

    /**
     * @param string $name
     *
     * @return PropertyBuilder
     */
    public function createBooleanProperty(string $name): PropertyBuilder
    {
        $definition = PropertyDefinition::fromString($name, BooleanType::class);
        $this->document->createProperty($definition);

        return new PropertyBuilder($definition, $this->document, $this);
    }

    /**
     * @return DocumentDesigner
     */
    public function build(): DocumentDesigner
    {
        return $this->document;
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
