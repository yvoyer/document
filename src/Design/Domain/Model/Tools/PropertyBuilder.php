<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Tools;

use Star\Component\Document\Design\Domain\Model\Definition\RequiredProperty;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class PropertyBuilder
{
    /**
     * @var PropertyDefinition
     */
    private $definition;

    /**
     * @var DocumentDesigner
     */
    private $document;

    /**
     * @var DocumentBuilder
     */
    private $builder;

    /**
     * @param PropertyDefinition $definition
     * @param DocumentDesigner $document
     * @param DocumentBuilder $builder
     */
    public function __construct(
        PropertyDefinition $definition,
        DocumentDesigner $document,
        DocumentBuilder $builder
    ) {
        $this->definition = $definition;
        $this->document = $document;
        $this->builder = $builder;
    }

    /**
     * @return PropertyBuilder
     */
    public function required(): self
    {
        $this->document->changePropertyAttribute(
            $this->definition->getName(),
            new RequiredProperty()
        );

        return $this;
    }

    /**
     * @return DocumentBuilder
     */
    public function endProperty(): DocumentBuilder
    {
        return $this->builder;
    }
}
