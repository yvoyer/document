<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerRegistry;

abstract class PropertyBuilder
{
    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var DocumentDesigner
     */
    private $document;

    /**
     * @var DocumentBuilder
     */
    private $builder;

    /**
     * @var TransformerRegistry
     */
    private $factory;

    final public function __construct(
        PropertyName $name,
        DocumentDesigner $document,
        DocumentBuilder $builder,
        TransformerRegistry $factory
    ) {
        $this->name = $name;
        $this->document = $document;
        $this->builder = $builder;
        $this->factory = $factory;
    }

    public function withConstraint(string $name, PropertyConstraint $constraint): self
    {
        $this->document->addPropertyConstraint($this->name, $name, $constraint);

        return $this;
    }

    public function transformedWith(string $transformer): self
    {
        $this->document->addPropertyTransformer($this->name, TransformerIdentifier::fromString($transformer));

        return $this;
    }

    public function endProperty(): DocumentBuilder
    {
        return $this->builder;
    }

    public function buildDocument(): DocumentDesigner
    {
        return $this->builder->getDocument();
    }
}
