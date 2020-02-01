<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use Star\Component\Document\Design\Domain\Model\Constraints\RequiresValue;
use Star\Component\Document\Design\Domain\Model\Constraints\RequiresSingleOption;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerRegistry;
use Star\Component\Document\Design\Domain\Model\Transformation\ValueTransformer;

final class PropertyBuilder
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

    public function __construct(
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

    public function required(): self
    {
        $this->document->addPropertyConstraint(
            $this->name,
            'required',
            new RequiresValue()
        );

        return $this;
    }

    public function singleOption(): self
    {
        $this->document->addPropertyConstraint(
            $this->name,
            'single-option',
            new RequiresSingleOption()
        );

        return $this;
    }

    public function withTransformer(ValueTransformer $transformer): self
    {
        $this->factory->registerTransformer(\get_class($transformer), $transformer);

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
