<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

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

    final public function __construct(
        PropertyName $name,
        DocumentDesigner $document,
        DocumentBuilder $builder
    ) {
        $this->name = $name;
        $this->document = $document;
        $this->builder = $builder;
    }

    public function withConstraint(string $constraintName, PropertyConstraint $constraint): self
    {
        $this->document->addPropertyConstraint($this->name, $constraintName, $constraint);

        return $this;
    }

    public function withParameter(string $parameterName, PropertyParameter $parameter): self
    {
        $this->document->addPropertyParameter($this->name, $parameterName, $parameter);

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

    protected function constraints(): ConstraintBuilder
    {
        return $this->builder::constraints();
    }

    protected function parameters(): ParameterBuilder
    {
        return $this->builder::parameters();
    }
}
